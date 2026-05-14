@extends('layouts.dashboard')

@section('title', 'Messages')

@section('styles')
    @vite(['resources/css/messages.css'])
    <style>
        /* Override layout padding for full-screen chat experience */
        .main { padding: 0 !important; height: 100vh; display: flex; flex-direction: column; }
        .app-container { height: 100vh; overflow: hidden; }
    </style>
@endsection

@section('content')
<div class="chat-layout" style="height: 100%;">
  <!-- ═══ CONTACTS PANEL ═══ -->
  <aside class="contacts-panel">
    <div class="cp-header">
      <h2 class="cp-title">Inbox</h2>
      <div class="cp-header-actions">
        <button class="icon-btn">✎</button>
        <button class="icon-btn">⋮</button>
      </div>
    </div>

    <div class="cp-search">
      <div class="search-wrap">
        <span class="search-ico">🔍</span>
        <input type="text" placeholder="Search conversations...">
      </div>
    </div>

    <div class="cp-filters">
      <div class="cf-tab active">All</div>
      <div class="cf-tab">Unread</div>
      <div class="cf-tab">Archived</div>
    </div>

    <div class="contact-list">
      @if(auth()->user()->role === 'recruiter' && count($candidates) > 0)
      <div class="contact-divider">Applied Candidates</div>
      <div style="display:flex; gap:12px; overflow-x:auto; padding:0 20px 16px; margin-bottom:16px; border-bottom:1px solid var(--border);">
        @foreach($candidates as $candidate)
        <a href="{{ route('recruiter.messages.start', $candidate->id) }}" style="text-decoration:none; display:flex; flex-direction:column; align-items:center; gap:6px; min-width:60px;">
          <div class="ci-avatar" style="background:var(--s2); color:var(--teal); width:44px; height:44px; margin:0;">{{ substr($candidate->name, 0, 1) }}</div>
          <div style="font-size:10px; color:var(--text3); text-align:center; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; width:100%;">{{ explode(' ', $candidate->name)[0] }}</div>
        </a>
        @endforeach
      </div>
      @endif

      <div class="contact-divider">Recent Conversations</div>

      @forelse($conversations as $conv)
        @php
            $otherUser = $conv->user_one_id === auth()->id() ? $conv->userTwo : $conv->userOne;
            $lastMsg = $conv->messages()->latest()->first();
        @endphp
        <a href="{{ route('messages.show', $conv) }}" class="contact-item {{ isset($activeConversation) && $activeConversation->id === $conv->id ? 'active' : '' }}" style="text-decoration: none;">
          <div class="ci-avatar" style="background: linear-gradient(135deg, var(--teal), var(--sky)); color: #060912;">
            {{ substr($otherUser->name, 0, 1) }}
            <div class="ci-status status-online"></div>
          </div>
          <div class="ci-info">
            <div class="ci-name">{{ $otherUser->name }}</div>
            <div class="ci-preview {{ $lastMsg && !$lastMsg->is_read && $lastMsg->sender_id !== auth()->id() ? 'unread' : '' }}">
              {{ $lastMsg ? $lastMsg->content : 'No messages yet' }}
            </div>
          </div>
          <div class="ci-meta">
            <div class="ci-time">{{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(null, true) : '' }}</div>
            @php
                $unreadCount = $conv->messages()->where('sender_id', '!=', auth()->id())->where('is_read', false)->count();
            @endphp
            @if($unreadCount > 0)
                <div class="ci-unread">{{ $unreadCount }}</div>
            @endif
          </div>
        </a>
      @empty
        <div style="padding: 20px; text-align: center; color: var(--text3); font-size: 13px;">
            No conversations yet.
        </div>
      @endforelse
    </div>
  </aside>

  <!-- ═══ CHAT PANEL ═══ -->
  <main class="chat-panel">
    @if(isset($activeConversation))
        @php
            $otherUser = $activeConversation->user_one_id === auth()->id() ? $activeConversation->userTwo : $activeConversation->userOne;
        @endphp
        <header class="chat-header">
          <div class="ch-avatar" style="background: linear-gradient(135deg, var(--violet), var(--sky)); color: #060912;">
            {{ substr($otherUser->name, 0, 1) }}
          </div>
          <div>
            <div class="ch-name">{{ $otherUser->name }}</div>
            <div class="ch-sub">
              <div class="ch-online-dot"></div>
              Active Now
            </div>
          </div>

          <div class="ch-actions">
            <button class="icon-btn">📞</button>
            <button class="icon-btn">🎥</button>
            <button class="icon-btn" onclick="toggleContext()">ⓘ</button>
          </div>
        </header>

        @if($activeConversation->jobPost)
        <div class="job-context-strip">
          <div class="jcs-logo" style="background: linear-gradient(135deg, var(--teal), var(--sky)); color: #060912;">
            {{ substr($activeConversation->jobPost->title, 0, 1) }}
          </div>
          <div class="jcs-info">
            <div class="jcs-title">{{ $activeConversation->jobPost->title }}</div>
            <div class="jcs-sub">{{ $activeConversation->jobPost->company ?? 'JobFlow' }} · {{ $activeConversation->jobPost->location ?? 'Remote' }}</div>
          </div>
          <div class="jcs-match">{{ (int) $activeConversation->jobPost->match }}% Match</div>
        </div>
        @endif

        <div class="messages-area" id="messagesArea">
          @php $currentDate = null; @endphp
          @foreach($activeConversation->messages as $msg)
            @php
                $msgDate = $msg->created_at->format('Y-m-d');
            @endphp
            @if($currentDate !== $msgDate)
                <div class="date-divider"><span>{{ $msg->created_at->isToday() ? 'Today' : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('M d, Y')) }}</span></div>
                @php $currentDate = $msgDate; @endphp
            @endif

            <div class="msg-row {{ $msg->sender_id === auth()->id() ? 'outgoing' : 'incoming' }}">
              <div class="msg-avatar-sm" style="background: var(--s2); color: var(--teal);">{{ substr($msg->sender->name, 0, 1) }}</div>
              <div class="bubble-wrap">
                <div class="bubble {{ $msg->sender_id === auth()->id() ? 'outgoing' : 'incoming' }}">
                  {{ $msg->content }}
                </div>
                <div class="bubble-meta">
                  {{ $msg->created_at->format('H:i') }}
                  @if($msg->sender_id === auth()->id())
                    <span class="read-ticks {{ $msg->is_read ? 'read' : 'sent' }}">✓✓</span>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="input-area">
          <form action="{{ route('messages.store', $activeConversation) }}" method="POST" class="input-row" id="messageForm">
            @csrf
            <button type="button" class="icon-btn">⊕</button>
            <div class="msg-input-wrap">
              <textarea name="content" class="msg-input" placeholder="Type a message..." rows="1" id="messageInput" required></textarea>
            </div>
            <button type="submit" class="send-btn">➤</button>
          </form>
        </div>
    @else
        <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text3);">
            <div style="font-size: 64px; margin-bottom: 24px;">💬</div>
            <div style="font-family: 'DM Serif Display', serif; font-size: 24px; color: var(--text); margin-bottom: 8px;">Select a conversation</div>
            <div style="font-size: 14px;">Choose from the list on the left to start chatting</div>
        </div>
    @endif
  </main>

  @if(isset($activeConversation))
  <aside class="context-panel" id="contextPanel">
    <div class="cxp-header">
      <h3 class="cxp-title">Details</h3>
      <button class="icon-btn" onclick="toggleContext()">✕</button>
    </div>
    <div class="cxp-body">
      <div class="cxp-profile">
        <div class="cxp-avatar" style="background: linear-gradient(135deg, var(--teal), var(--sky)); color: #060912;">
          {{ substr($otherUser->name, 0, 1) }}
        </div>
        <div class="cxp-name">{{ $otherUser->name }}</div>
        <div class="cxp-role">{{ $otherUser->role === 'recruiter' ? 'Recruiter' : 'Candidate' }}</div>
      </div>

      <div class="cxp-section">
        <div class="cxp-sec-label">Contact Information</div>
        <div style="font-size: 12px; color: var(--text2); display: flex; flex-direction: column; gap: 8px;">
          <div style="display: flex; align-items: center; gap: 8px;">📧 {{ $otherUser->email }}</div>
          <div style="display: flex; align-items: center; gap: 8px;">📍 San Francisco, CA</div>
        </div>
      </div>
    </div>
  </aside>
  @endif
</div>
@endsection

@section('scripts')
<script>
    function toggleContext() {
        const panel = document.getElementById('contextPanel');
        if (panel) panel.classList.toggle('open');
    }

    const messagesArea = document.getElementById('messagesArea');
    if (messagesArea) {
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }

    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('messageForm').submit();
            }
        });
    }
</script>
@endsection
