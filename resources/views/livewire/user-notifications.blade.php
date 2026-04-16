<div 
    x-data="{ show: false }"
    x-init="
        show = false;
        window.openNotifications = () => { show = true };

        const userId = @js(auth()->id());
        if (userId && window.Echo && !window.__userNotificationsRealtimeBound) {
            window.__userNotificationsRealtimeBound = true;
            window.Echo.private(`user.${userId}`)
                .listen('.notification.created', () => {
                    $wire.$refresh();
                    if (window.Livewire) {
                        window.Livewire.dispatch('notificationsUpdated');
                    }
                });
        }
    "
>
    <!-- Notifications Modal -->
    <div 
        x-show="show"
        x-cloak
        x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[500] flex items-center justify-center p-6"
        @click.self="show = false"
        @keydown.escape.window="show = false"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-zinc-950/60 backdrop-blur-3xl"></div>

        <!-- Modal -->
        <div 
            class="relative w-full max-w-2xl max-h-[85vh] bg-zinc-950/60 border border-zinc-800 rounded-[3rem] shadow-[0_50px_100px_rgba(0,0,0,0.8)] overflow-hidden flex flex-col backdrop-blur-3xl"
            @click.stop
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
            x-transition:enter-start="opacity-0 scale-95 translate-y-20 blur-xl"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0 blur-0"
        >
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>

            <!-- Header -->
            <div class="shrink-0 p-10 border-b border-zinc-800/50 bg-zinc-950/40 relative">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-zinc-950 border border-zinc-800 flex items-center justify-center shadow-inner">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-white uppercase tracking-tighter italic">{{ __('Your') }} <span class="text-emerald-500">{{ __('Notifications') }}</span></h2>
                            <p class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em] mt-1 italic">
                                @if($this->unreadCount > 0)
                                    {{ __(':count unread notifications', ['count' => $this->unreadCount]) }}
                                @else
                                    {{ __('You are all caught up') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        @if($this->unreadCount > 0)
                            <button 
                                wire:click="markAllAsRead"
                                class="px-6 py-3 bg-zinc-950 border border-emerald-500/30 text-emerald-500 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-500 hover:text-black transition-all italic">
                                {{ __('Mark all as read') }}
                            </button>
                        @endif
                        <button 
                            type="button"
                            class="w-12 h-12 rounded-2xl bg-zinc-950 border border-zinc-800 flex items-center justify-center text-zinc-600 hover:text-white transition-all shadow-inner"
                            @click="show = false"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="flex-1 overflow-y-auto custom-scrollbar bg-zinc-900/40 p-10 space-y-6">
                @if($showInvitationDecision)
                    <div class="p-8 rounded-[2rem] border border-cyan-500/30 bg-cyan-500/[0.04] shadow-[0_20px_40px_rgba(6,182,212,0.08)]">
                        <p class="text-[10px] font-black text-cyan-400 uppercase tracking-[0.35em]">{{ __('Invitation') }}</p>
                        <p class="mt-3 text-white text-sm font-black uppercase tracking-tight">
                            {{ __(':company invited you to join.', ['company' => $invitationCompanyName ?? __('Company')]) }}
                        </p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <button
                                type="button"
                                wire:click="acceptInvitation"
                                class="px-6 py-3 bg-emerald-500 text-black text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-400 transition-all"
                            >
                                {{ __('Accept') }}
                            </button>
                            <button
                                type="button"
                                wire:click="rejectInvitation"
                                class="px-6 py-3 bg-rose-500/15 border border-rose-500/30 text-rose-400 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-500/25 transition-all"
                            >
                                {{ __('Reject') }}
                            </button>
                            <button
                                type="button"
                                wire:click="closeInvitationDecision"
                                class="px-6 py-3 bg-zinc-950 border border-zinc-800 text-zinc-400 text-[9px] font-black uppercase tracking-widest rounded-xl hover:text-white transition-all"
                            >
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </div>
                @endif

                @forelse($notifications as $index => $notification)
                    <div 
                        class="p-8 rounded-[2rem] border transition-all duration-700 group/signal {{ !$notification->is_read ? 'bg-emerald-500/[0.03] border-emerald-500/20 shadow-[0_20px_40px_rgba(16,185,129,0.05)]' : 'bg-zinc-950/40 border-zinc-800/50 opacity-60 hover:opacity-100' }}"
                        x-init="setTimeout(() => $el.classList.add('translate-y-0', 'opacity-100'), {{ $index * 50 }})"
                    >
                        <div class="flex items-start gap-6">
                            <!-- Icon Matrix -->
                            <div class="shrink-0">
                                @php
                                    $iconColor = match($notification->type) {
                                        'welcome' => 'text-cyan-500',
                                        'follow' => 'text-emerald-500',
                                        'new_post_from_following' => 'text-emerald-400',
                                        'suspension_user_expired', 'suspension_post_expired', 'post_suspended' => 'text-rose-500',
                                        default => 'text-zinc-600'
                                    };
                                    $bgAlpha = match($notification->type) {
                                        'welcome', 'follow', 'new_post_from_following' => 'bg-emerald-500/10',
                                        'suspension_user_expired', 'suspension_post_expired', 'post_suspended' => 'bg-rose-500/10',
                                        default => 'bg-zinc-950'
                                    };
                                @endphp
                                <div class="w-12 h-12 rounded-2xl {{ $bgAlpha }} border border-white/5 flex items-center justify-center shadow-inner">
                                    <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        @switch($notification->type)
                                            @case('welcome') <path d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10.011 10.011 0 0012 2z" /> @break
                                            @case('follow') <path d="M18 9a3 3 0 11-3-3 3 3 0 013 3zm-2 8a4 4 0 00-8 0v1h8z" /> @break
                                            @case('new_post_from_following') <path d="M5 5h14M5 9h14M5 15h10M5 19h6" /> @break
                                            @case('post_suspended') <path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18" /> @break
                                            @default <path d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10.011 10.011 0 0012 2z" />
                                        @endswitch
                                    </svg>
                                </div>
                            </div>

                            <!-- Payload -->
                            <div class="flex-1 min-w-0">
                                @php
                                    $displayMessage = match($notification->type) {
                                        'follow' => $notification->sourceUser ? __(':name started following you.', ['name' => $notification->sourceUser->name]) : $notification->message,
                                        'new_message' => $notification->sourceUser ? __(':name sent you a new message.', ['name' => $notification->sourceUser->name]) : __('You received a new message.'),
                                        'new_comment' => $notification->sourceUser ? __(':name commented on your post.', ['name' => $notification->sourceUser->name]) : __('Someone commented on your post.'),
                                        'comment_reply' => $notification->sourceUser ? __(':name replied to your comment.', ['name' => $notification->sourceUser->name]) : __('Someone replied to your comment.'),
                                        'organization_invite' => $notification->sourceUser ? __(':name invited you to join their organization.', ['name' => $notification->sourceUser->name]) : $notification->message,
                                        'organization_invite_accepted' => $notification->sourceUser ? __(':name accepted your organization invitation.', ['name' => $notification->sourceUser->name]) : $notification->message,
                                        'organization_invite_rejected' => $notification->sourceUser ? __(':name declined your organization invitation.', ['name' => $notification->sourceUser->name]) : $notification->message,
                                        'new_post' => $notification->sourceUser ? __(':name published a new post.', ['name' => $notification->sourceUser->name]) : __('A new post is available.'),
                                        'new_post_from_following' => $notification->sourceUser ? __(':name published a new post.', ['name' => $notification->sourceUser->name]) : __('A new post is available from someone you follow.'),
                                        'endorsement' => $notification->sourceUser ? __(':name endorsed your profile.', ['name' => $notification->sourceUser->name]) : __('You received a new endorsement.'),
                                        'job_application' => __('You have a new job application.'),
                                        'application_status' => __('Your application status has been updated.'),
                                        'cv_uploaded' => $notification->sourceUser ? __(':name uploaded a CV to your post.', ['name' => $notification->sourceUser->name]) : __('A new CV was uploaded to your post.'),
                                        'job_alert' => __('You have a new job alert.'),
                                        'post_suspended' => __('Your post has been suspended.'),
                                        'suspension_user_expired' => __('A user suspension has expired.'),
                                        'suspension_post_expired' => __('A post suspension has expired.'),
                                        'welcome' => __('Welcome to Career Hub!'),
                                        default => __($notification->message),
                                    };

                                    // Localize legacy stored English notification messages.
                                    if (preg_match('/^(.+)\sstarted following you\.$/u', (string) $notification->message, $m)) {
                                        $displayMessage = __(':name started following you.', ['name' => $m[1]]);
                                    } elseif (preg_match('/^(.+)\shas accepted your organization invitation\.$/u', (string) $notification->message, $m)) {
                                        $displayMessage = __(':name accepted your organization invitation.', ['name' => $m[1]]);
                                    } elseif (preg_match('/^(.+)\shas declined your organization invitation\.$/u', (string) $notification->message, $m)) {
                                        $displayMessage = __(':name declined your organization invitation.', ['name' => $m[1]]);
                                    } elseif (preg_match('/^(.+)\sinvited you to join\.$/u', (string) $notification->message, $m)) {
                                        $displayMessage = __(':name invited you to join their organization.', ['name' => $m[1]]);
                                    }
                                @endphp
                                <p class="text-[13px] font-black italic {{ !$notification->is_read ? 'text-white' : 'text-zinc-500' }} tracking-tight uppercase leading-relaxed font-medium">"{{ $displayMessage }}"</p>
                                <div class="flex items-center gap-6 mt-4 flex-wrap">
                                    <span class="text-[9px] font-black text-zinc-700 uppercase tracking-widest italic">{{ __('Logged') }} {{ $notification->created_at->diffForHumans() }}</span>

                                    @if($notification->post)
                                        <a href="{{ route('posts.show', $notification->post->slug) }}" class="text-[9px] font-black text-emerald-500 uppercase tracking-widest hover:text-emerald-400 transition-colors italic border-b border-emerald-500/20 pb-0.5" @click="show = false">{{ __('View post') }} →</a>
                                    @endif

                                    @if($notification->type === 'follow' && $notification->sourceUser)
                                        <a
                                            href="{{ route('user.profile', $notification->sourceUser->username ?? 'unknown') }}"
                                            class="text-[9px] font-black text-emerald-500 uppercase tracking-widest hover:text-emerald-400 transition-colors italic border-b border-emerald-500/20 pb-0.5"
                                            @click="show = false"
                                        >
                                            {{ __('View profile') }} →
                                        </a>
                                    @endif

                                    @if($notification->type === 'organization_invite' && auth()->check() && auth()->id() === $notification->user_id)
                                        <button type="button" wire:click="openInvitationDecision({{ $notification->id }})" class="text-[9px] font-black text-cyan-500 uppercase tracking-widest hover:text-cyan-400 transition-colors italic">
                                            {{ __('View invitation') }} →
                                        </button>
                                    @endif

                                    @if(in_array($notification->type, ['organization_invite_accepted', 'organization_invite_rejected']) && $notification->sourceUser)
                                        <a href="{{ route('user.profile', $notification->sourceUser->username ?? 'unknown') }}" class="text-[9px] font-black text-emerald-500 uppercase tracking-widest hover:text-emerald-400 transition-colors italic" @click="show = false">{{ __('View profile') }} →</a>
                                    @endif
                                </div>
                            </div>
                            @if(!$notification->is_read)
                                <button wire:click="markAsRead({{ $notification->id }})" class="w-10 h-10 rounded-xl bg-zinc-950 border border-zinc-800 flex items-center justify-center text-zinc-900 group-hover/signal:text-emerald-500/50 hover:!text-emerald-500 transition-all shadow-inner" title="{{ __('Mark as read') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center flex flex-col items-center">
                        <div class="w-20 h-20 rounded-3xl bg-zinc-950 border border-zinc-800 flex items-center justify-center mb-8 shadow-inner"><svg class="w-8 h-8 text-zinc-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg></div>
                        <h3 class="text-xl font-black text-white italic uppercase tracking-tighter">{{ __('No notifications') }}</h3>
                        <p class="mt-2 text-[10px] font-black text-zinc-700 uppercase tracking-[0.4em] italic">{{ __('You are all caught up') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Matrix -->
            @if($notifications->hasPages())
                <div class="shrink-0 p-8 border-t border-zinc-800/50 bg-zinc-950/20">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
