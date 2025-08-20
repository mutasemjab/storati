<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.Story') }} - {{ $celebrity->name_ar }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #000;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }
        .story-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0,0,0,0.5);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            z-index: 10;
        }
        .progress-container {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 80px;
            height: 4px;
            display: flex;
            gap: 4px;
            z-index: 10;
        }
        .progress-bar {
            flex: 1;
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: white;
            width: 0%;
            transition: width 0.1s linear;
        }
        .progress-fill.active {
            animation: progress 5s linear forwards;
        }
        .celebrity-info {
            position: absolute;
            top: 40px;
            left: 20px;
            display: flex;
            align-items: center;
            color: white;
            z-index: 10;
        }
        .celebrity-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid white;
            margin-right: 10px;
        }
        .celebrity-name {
            font-weight: bold;
            font-size: 16px;
        }
        .story-time {
            font-size: 12px;
            opacity: 0.8;
        }
        .navigation {
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            display: flex;
        }
        .nav-area {
            flex: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .nav-area:hover {
            background: rgba(255,255,255,0.1);
        }
        .nav-icon {
            color: white;
            font-size: 30px;
            opacity: 0.7;
        }
        .story-media {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .story-caption {
            position: absolute;
            bottom: 60px;
            left: 20px;
            right: 20px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 15px;
            border-radius: 10px;
            z-index: 10;
        }
        .story-meta {
            position: absolute;
            bottom: 20px;
            right: 20px;
            color: white;
            font-size: 14px;
            opacity: 0.8;
            z-index: 10;
        }
        .content-area {
            flex: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }
        @keyframes progress {
            from { width: 0%; }
            to { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="story-container">
        <!-- Close button -->
        <button class="close-btn" onclick="closeStory()">×</button>

        <!-- Progress bars -->
        <div class="progress-container">
            @foreach($stories as $index => $storyItem)
                <div class="progress-bar">
                    <div class="progress-fill {{ $index == $currentIndex ? 'active' : '' }}" 
                         style="width: {{ $index < $currentIndex ? '100%' : '0%' }}"
                         id="progress-{{ $index }}"></div>
                </div>
            @endforeach
        </div>

        <!-- Celebrity info -->
        <div class="celebrity-info">
            <img src="{{ asset('assets/admin/uploads/' . $celebrity->photo) }}" 
                 alt="{{ $celebrity->name_ar }}"
                 class="celebrity-avatar">
            <div>
                <div class="celebrity-name">{{ $celebrity->name_ar }}</div>
                <div class="story-time">{{ $story->created_at->diffForHumans() }}</div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="navigation">
            <!-- Previous area -->
            <div class="nav-area" onclick="previousStory()">
                @if($currentIndex > 0)
                    <div class="nav-icon">‹</div>
                @endif
            </div>

            <!-- Content area -->
            <div class="content-area">
                @if($story->type === 'photo')
                    <img src="{{ $story->media_url }}" alt="Story" class="story-media" id="storyMedia">
                @else
                    <video controls class="story-media" id="storyMedia">
                        <source src="{{ $story->media_url }}" type="video/mp4">
                        {{ __('messages.Your browser does not support the video tag') }}
                    </video>
                @endif
            </div>

            <!-- Next area -->
            <div class="nav-area" onclick="nextStory()">
                @if($currentIndex < count($stories) - 1)
                    <div class="nav-icon">›</div>
                @endif
            </div>
        </div>

        <!-- Caption -->
        @if($story->caption)
            <div class="story-caption">
                {{ $story->caption }}
            </div>
        @endif

        <!-- Meta info -->
        <div class="story-meta">
            <i class="fas fa-eye"></i> {{ number_format($story->views_count) }}
        </div>
    </div>

    <script>
        let currentStoryIndex = {{ $currentIndex }};
        let stories = @json($stories->values());
        let autoPlayTimer;

        function startAutoPlay() {
            if (stories[currentStoryIndex].type === 'photo') {
                autoPlayTimer = setTimeout(() => {
                    nextStory();
                }, 5000);
            }
        }

        function stopAutoPlay() {
            if (autoPlayTimer) {
                clearTimeout(autoPlayTimer);
            }
        }

        function nextStory() {
            stopAutoPlay();
            if (currentStoryIndex < stories.length - 1) {
                window.location.href = `/celebrities/{{ $celebrity->id }}/stories/${stories[currentStoryIndex + 1].id}`;
            } else {
                closeStory();
            }
        }

        function previousStory() {
            stopAutoPlay();
            if (currentStoryIndex > 0) {
                window.location.href = `/celebrities/{{ $celebrity->id }}/stories/${stories[currentStoryIndex - 1].id}`;
            }
        }

        function closeStory() {
            stopAutoPlay();
            window.history.back();
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            switch(e.key) {
                case 'ArrowLeft':
                    previousStory();
                    break;
                case 'ArrowRight':
                case ' ':
                    e.preventDefault();
                    nextStory();
                    break;
                case 'Escape':
                    closeStory();
                    break;
            }
        });

        // Touch support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            if (touchEndX < touchStartX - swipeThreshold) {
                nextStory();
            }
            if (touchEndX > touchStartX + swipeThreshold) {
                previousStory();
            }
        }

        // Start auto-play
        document.addEventListener('DOMContentLoaded', function() {
            startAutoPlay();
        });

        // Pause auto-play on interaction
        document.addEventListener('click', function() {
            stopAutoPlay();
            startAutoPlay();
        });
    </script>
</body>
</html>