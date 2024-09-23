<div>
    <!-- ======= Header ======= -->
    <i style="background-color: #7a6ad8;" wire:ignore class="bi bi-list mobile-nav-toggle d-lg-none text-white"></i>
    <header id="header" class="d-flex flex-column justify-content-center">

        <nav id="navbar" class="navbar nav-menu">
            <ul>
                <li><a wire:click="resetMessage" role="button" class="nav-link scrollto @if($sendMessage == false) active @endif"><i class="bx bx-home"></i>
                        <span>Home</span></a>
                </li>
                <li><a href="{{ route('profile.show') }}" class="nav-link scrollto"><i class="bx bx-user"></i>
                        <span>{{ Auth::user()->name }}</span></a>
                </li>
                @if($sendMessage == true)
                <li><a href="#resume" class="nav-link scrollto @if($sendMessage == true) active @endif"><i class="bx bx-chat"></i>
                        <span>{{$recipientName}}</span></a>
                </li>
                @endif
                <li><a href="#portfolio" class="nav-link scrollto"><i class="bx bx-book-content"></i>
                        <span>Contacten</span></a></li>
                <li><a href="#services" class="nav-link scrollto"><i class="bx bx-server"></i>
                        <span>Services</span></a>
                </li>
                <li><a href="#contact" class="nav-link scrollto"><i class="bx bx-envelope"></i>
                        <span>Berichten</span></a>
                </li>
            </ul>
        </nav>
        <!-- End nav-menu -->
    </header>
    <!-- End Header -->
    <!-- ======= Chat Section ======= -->
    <section id="chat" class="chat p-0">

        <div style="min-height: 500px;" class="container-fluid ps-4 border mb-0">

            <div class="row">
                @include('chat.users')

                <!--Start Chat Block-->
                @if ($sendMessage == true)
                    <div class="col-lg-9 col-12 mt-0 border-end">
                        <div class="container border-bottom p-2 ps-0">
                            <div class="d-none d-lg-flex justify-content-between">
                                <div class="">
                                    @if ($this->recipientPhoto == null)
                                        <img style="max-width:80px;max-height:80px;"
                                            class="primg border bg-secondary w-100 h-100 border-3 rounded-circle @if ($loggedIn) border-success @else border-danger @endif"
                                            src="{{ $recipientImage }}" alt="">
                                    @else
                                        <img style="max-width:70px;max-height:70px;"
                                            class="primg border bg-secondary w-100 h-100 border-3 rounded-circle @if ($loggedIn) border-success @else border-danger @endif"
                                            src="/storage/app/public/{{ $recipientPhoto }}" alt="">
                                    @endif

                                </div>

                                <div class="d-flex align-items-center">
                                    <i class="top-icon bi bi-search p-3 d-none d-lg-flex"></i>
                                    <i class="top-icon bi bi-telephone-forward p-3 d-none d-lg-flex"></i>
                                    <i role="button" class="top-icon bi bi-camera-video p-3 "></i>
                                    <i class="top-icon bi bi-bookmark p-3 d-none d-lg-flex"></i>
                                    <i class="top-icon bi bi-info-circle-fill p-3 d-none d-lg-flex"></i>

                                    <div wire:ignore class="dropdown">
                                        <a class="" role="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="top-icon bi bi-three-dots-vertical p-3"></i>
                                        </a>
                                        <ul class="dropdown-menu p-0">
                                            <li><a wire:click="resetMessage"
                                                    class="dropdown-item border-bottom"role="button">
                                                    <h6 class="pt-2">Sluit
                                                        Chat
                                                    </h6>
                                                </a>
                                            </li>
                                            <li><a class="btn border-bottom dropdown-item rounded-0"
                                                    href="{{ route('profile.show') }}">
                                                    <h6 class="pt-2">Instellingen</h6>
                                                </a>
                                            </li>
                                            <li>
                                                <form class="p-0 m-0" action="{{ url('logout') }}" method="POST">
                                                    @csrf
                                                    <button class="btn dropdown-item rounded-0" type="submit">
                                                        <h6 class="pt-2 text-capitalize">
                                                            {{ Auth::user()->name }} Log Uit</h6>
                                                    </button>
                                                </form>

                                            </li>
                                        </ul>
                                    </div>
                                    <i class="top-icon bi bi-gear p-3"></i>
                                </div>
                            </div>
                        </div>
                        <style>
                            .messages {
                                min-height: 400px;
                                max-height: 400px;
                                overflow-y: auto;
                                background-image: url('assets/img/bg.jpg');
                            }

                            @media (max-width : 600px) {
                                .messages {
                                    min-height: 600px;
                                    max-height: 600px;
                                }
                            }
                        </style>
                        <form
                            @if ($message || $bestand) wire:submit="saveMessage({{ $this->recipientId }})" @else wire:submit="$refresh" @endif>
                            <div class="row messages zijdebar border-bottom ps-0">
                                @foreach ($messages as $message)
                                    <!--Sender-->
                                    @if ($message['sender_id'] == Auth::user()->id && $message['recipient_id'] == $this->recipientId)
                                        <div wire:poll class="container">
                                            <div style="overflow-x:break-word;word-break:break-end;"
                                                class="d-flex mb-1 pt-3">
                                                <div class="dropdown">
                                                    <a class="" type="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        @if (Auth::user()->profile_photo_path == null)
                                                            <img class="rounded-circle border border-3 bg-secondary @if (Auth::user()->hasLogin(Auth::user())) border-success @else border-danger @endif"
                                                                src="{{ Auth::user()->profile_photo_url }}"
                                                                alt="">
                                                        @else
                                                            <img style="width:80px;height:80px;"
                                                                class="img rounded-circle border border-3 @if (Auth::user()->hasLogin(Auth::user())) border-success @else border-danger @endif"
                                                                src="/storage/app/public/{{ Auth::user()->profile_photo_path }}"
                                                                alt="">
                                                        @endif
                                                    </a>
                                                    <ul wire:ignore.self class="dropdown-menu chat-options">
                                                        <small
                                                            class="chat-time ms-3 h-50 text-primary">{{ $message->created_at->diffForHumans() }}</small>
                                                        <li><a wire:click="delete({{ $message->id }})"
                                                                wire:confirm="Weet je het zeker?" role="button"
                                                                class="btn btn dropdown-item">Verwijder
                                                                Bericht</a></li>
                                                        <li><a wire:click="deleteMessage"
                                                                wire:confirm="Weet je het zeker?"
                                                                class="btn dropdown-item">Verwijder Chat</a></li>
                                                        <li><a wire:click="resetMessage" class="btn dropdown-item">Sluit
                                                                Chat</a></li>
                                                    </ul>
                                                </div>
                                                <p class="d-block sender bg-light p-2 rounded-4">
                                                    <small style="font-weight: 900;"
                                                        class="d-flex justify-content-start me-2 text-dark">
                                                        {{ $message['created_at']->diffForHumans() }}</small>
                                                    <small class="">
                                                        @if ($message->message)
                                                            @if (str_contains($message->message, 'http'))
                                                                <a class="text-primary" href="{{ $message->message }}"
                                                                    target="blank">
                                                                    {{ $message->message }}
                                                                </a>
                                                            @else
                                                                {{ $message->message }} <br>
                                                            @endif
                                                        @endif
                                                        @if ($message->bestand)
                                                            @if (str_contains($message->bestand, 'mp4'))
                                                                <video width="200" height="300" class="rounded"
                                                                    controls>
                                                                    <source
                                                                        src="/storage/app/public/{{ $message->bestand }}"
                                                                        type="video/mp4">
                                                                    Your browser does not support HTML video.
                                                                </video>
                                                            @elseif (str_contains($message->bestand, 'mp3'))
                                                                <audio controls>
                                                                    <source
                                                                        src="/storage/app/public/{{ $message->bestand }}"
                                                                        type="audio/ogg">
                                                                    <source
                                                                        src="/storage/app/public/{{ $message->bestand }}"
                                                                        type="audio/mpeg">
                                                                    Your browser does not support the audio element.
                                                                </audio>
                                                            @else
                                                                <a type="img" target="blank"
                                                                    href="/storage/app/public/{{ $message->bestand }}">
                                                                    <img class="rounded" style="width: 110px;"
                                                                        src="/storage/app/public/{{ $message->bestand }}"
                                                                        alt="{{ $message->bestand }}">
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    <!--End Sender-->
                                    <!--Receiver-->
                                    @if ($message['recipient_id'] == Auth::user()->id && $message['sender_id'] == $this->recipientId)
                                        <div class="container">
                                            <div class="d-flex justify-content-end mb-1 pt-3">
                                                <p class="d-block recipient p-2 rounded-4">
                                                    <small style="font-weight: 900;"
                                                        class="d-flex justify-content-end me-2 text-dark">

                                                        {{ $message['created_at']->diffForHumans() }}</small>

                                                    <small class="text-end me-2">
                                                        @if ($message->message)
                                                            @if (str_contains($message->message, 'http'))
                                                                <a href="{{ $message->message }}" target="blank">
                                                                    {{ $message->message }}
                                                                </a>
                                                            @else
                                                                {{ $message->message }} <br>
                                                            @endif
                                                        @endif
                                                        @if ($message->bestand)
                                                            @if (str_contains($message->bestand, 'mp4'))
                                                                <video width="200" height="300" class="rounded"
                                                                    controls>
                                                                    <source
                                                                        src="/storage/app/public/{{ $message->bestand }}"
                                                                        type="video/mp4">
                                                                    Your browser does not support HTML video.
                                                                </video>
                                                            @elseif (str_contains($message->bestand, 'mp3'))
                                                                <audio controls>
                                                                    <source
                                                                        src="/storage/app/public/{{ $message->bestand }}"
                                                                        type="audio/ogg">
                                                                    <source
                                                                        src="/storage/app/public/{{ $message->bestand }}"
                                                                        type="audio/mpeg">
                                                                    Your browser does not support the audio element.
                                                                </audio>
                                                            @else
                                                                <a type="pdf" target="blank"
                                                                    href="/storage/app/public/{{ $message->bestand }}">
                                                                    <img class="rounded" style="width: 110px;"
                                                                        src="/storage/app/public/{{ $message->bestand }}"
                                                                        alt="{{ $message->bestand }}">
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </small>
                                                </p>
                                                <div class="dropdown">
                                                    <a class="" role="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false" href="">
                                                        @if ($this->recipientPhoto == null)
                                                            <img class="rounded-circle border border-3 bg-secondary @if ($loggedIn) border-success @else border-danger @endif"
                                                                src="{{ $recipientImage }}" alt="">
                                                        @else
                                                            <img style="width:80px;height:80px;"
                                                                class="img rounded-circle border border-3 @if ($loggedIn) border-success @else border-danger @endif"
                                                                src="/storage/app/public/{{ $recipientPhoto }}"
                                                                alt="">
                                                        @endif
                                                    </a>

                                                    <ul wire:ignore.self class="dropdown-menu chat-options">
                                                        <small
                                                            class="chat-time ms-3 h-50 text-primary">{{ $message->created_at->diffForHumans() }}</small>
                                                        <li><a wire:click="delete({{ $message->id }})"
                                                                wire:confirm="Weet je het zeker?" role="button"
                                                                class="btn dropdown-item">Verwijder
                                                                Bericht</a></li>
                                                        <li><a wire:click="deleteMessage"
                                                                wire:confirm="Weet je het zeker?"
                                                                class="btn dropdown-item">Verwijder
                                                                Chat</a></li>
                                                        <li><a wire:click="resetMessage"
                                                                class="btn dropdown-item">Sluit Chat</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <!--End Receiver-->
                                @endforeach
                                @if ($bestand)
                                    <div
                                        class="col-lg-3 col-6 border border-top-0 border-start-0 ms-2 p-3 position-absolute bg-white">

                                        @if ($bestand->extension() == 'mp4')
                                            <video wire:ignore.self style="width: 140px;height:140px;" controls
                                                crossorigin playsinline oncontextmenu="return false;"
                                                controlsList="nodownload" class="">
                                                <!-- Video files -->
                                                <source src="{{ $bestand->temporaryUrl() }}"
                                                    type="video/{{ $bestand->extension() }}">
                                            </video>
                                        @else
                                            <img wire:ignore.self class="rounded m-0 p-0"
                                                type="image/{{ $bestand->extension() }}" style="height: 100px;"
                                                src="{{ $bestand->temporaryUrl() }}">
                                        @endif
                                        <br>
                                        <button wire:click="resetBestand"
                                            class="btn btn-outline-primary btn-sm mt-1">annuleer</button>
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>

                                    </div>
                                @endif
                            </div>

                            @error('message')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            @error('bestand')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <div class="d-flex align-items-center border border-3 rounded-5 mt-3 mb-2">

                                <div class="d-flex py-1 pe-1">
                                    <i class=" bi bi-three-dots p-3"></i>
                                    <i class="d-none d-lg-flex bi bi-emoji-smile p-3"></i>
                                </div>

                                <input style="box-shadow: none;" wire:model.live="message" type="text"
                                    class="form-control border-0" id="floatingInput" placeholder="Bericht">

                                <div class="d-flex align-items-center py-1 pe-1">


                                    <label class="file-upload d-lg-none p-0 w-100" for="fileUpload" role="button">
                                        <i class="bi bi-camera me-1 border rounded-circle p-3 text-center">
                                            <input wire:model.live="bestand" id="fileUpload" type="file" hidden>
                                        </i>
                                    </label>

                                    <label class="file-upload h-100 p-0 w-100" for="fileUpload" role="button">
                                        <i
                                            class="bi bi-paperclip d-none d-lg-flex me-1 border rounded-circle p-3 text-center">
                                            <input wire:model.live="bestand" id="fileUpload" type="file" hidden>
                                        </i>
                                    </label>

                                    <label class="file-upload h-100 p-0 w-100" for="fileUpload" role="button">
                                        <i
                                            class="bi bi-mic d-none d-lg-flex me-1 border rounded-circle p-3 text-center">
                                            <input wire:model.live="bestand" id="fileUpload" type="file" hidden>
                                        </i>
                                    </label>


                                    <button class="file-upload h-100 p-0 w-100 border-0 bg-transparent"
                                        type="submit">
                                        <i class="bi bi-send me-1 border rounded-circle p-3 text-center">
                                        </i>
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                    <!-- End Chat Block -->
                    <!--Start Front Page-->
                @elseif($sendMessage == false)
                    <div class="col-lg-9 col-12 mt-0 border-end">
                        <div class="container border-bottom p-2 ps-0">
                            <div class="d-flex justify-content-between">
                                @foreach ($users as $user)
                                    @if ($user->id == Auth::user()->id)
                                        <div class="d-flex">
                                            <div class="text-nowrap">
                                                @if ($user->profile_photo_path == null)
                                                    <img src="{{ $user->profile_photo_url }}"
                                                        class="primg border bg-secondary border-3 rounded-circle @if ($user->hasLogin($user)) border-success @else border-danger @endif"
                                                        alt="">
                                                @else
                                                    <img style="width:80px;height:80px;"
                                                        class="primg border bg-secondary border-3 rounded-circle @if ($user->hasLogin($user)) border-success @else border-danger @endif"
                                                        src="/storage/app/public/{{ $user->profile_photo_path }}"
                                                        alt="">
                                                @endif
                                            </div>
                                            <div class="ps-1">
                                                <h5>{{ ucwords(Auth::user()->name) }}</h5>
                                                @if ($user->hasLogin($user))
                                                    <h6>online</h6>
                                                @else
                                                    <h6>offline</h6>
                                                @endif
                                            </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="d-flex align-items-center">
                                <i class="top-icon bi bi-search p-3 d-none d-lg-flex" role="button"></i>
                                <i class="top-icon bi bi-telephone-forward p-3 d-none d-lg-flex"
                                    role="button"></i></button>
                                <i role="button" class="top-icon bi bi-camera-video p-3 "></i>
                                <i class="top-icon bi bi-bookmark p-3 d-none d-lg-flex" role="button"></i>
                                <i class="top-icon bi bi-info-circle-fill p-3 d-none d-lg-flex" role="button"></i>

                                <div wire:ignore class="dropdown">
                                    <a class="" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="top-icon bi bi-three-dots-vertical p-3"></i>
                                    </a>
                                    <ul class="dropdown-menu p-0">
                                        <li><a class="btn border-bottom dropdown-item rounded-0"
                                                href="{{ route('profile.show') }}">
                                                <h6 class="pt-2">Instellingen</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <form class="p-0 m-0" action="{{ url('logout') }}" method="POST">
                                                @csrf
                                                <button class="btn dropdown-item rounded-0" type="submit">
                                                    <h6 class="pt-2 text-capitalize">
                                                        {{ Auth::user()->name }} Log Uit</h6>
                                                </button>
                                            </form>

                                        </li>
                                    </ul>
                                </div>
                                <i class="top-icon bi bi-gear p-3"></i>
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-3">Start een chat</h4>

                    <div style="max-height: 400px;overflow-y:auto;" wire:poll class="row">

                        @foreach ($users as $user)
                            @php
                                $lastMessage = $user->messages->last();
                            @endphp
                            @if ($lastMessage && $user->isLastMessageFromCurrentUser($lastMessage, $user))
                                <div class=" mt-2">
                                    <a class="me-3 pt-4"
                                        @if ($lastMessage->recipient_id == Auth::user()->id) wire:click="getUser({{ $lastMessage->sender_id }})" @else wire:click="getUser({{ $lastMessage->message_id }})" @endif
                                        role="button">

                                        <div class="border rounded-4 pe-5 ps-2 d-flex align-items-center">
                                            <i class="">
                                                @if ($user->profile_photo_path == null)
                                                    <img style="height:40px;width:40px;"
                                                        class="animated fadeIn border border-3 rounded-circle @if ($user->hasLogin($user)) border-success @else border-danger @endif "
                                                        src="{{ $user->profile_photo_url }}" />
                                                @else
                                                    <img style="height:40px;width:40px;"
                                                        class="animated fadeIn border border-3 rounded-circle @if ($user->hasLogin($user)) border-success @else border-danger @endif "
                                                        src="/storage/app/public/{{ $user->profile_photo_path }}" />
                                                @endif
                                            </i>
                                            <h5 class="ps-2 d-flex align-items-center">

                                                @if ($lastMessage->message)
                                                    @if (str_contains($lastMessage->message, 'http'))
                                                        <a class="text-primary" href="{{ $lastMessage->message }}"
                                                            target="blank">
                                                            {{ $lastMessage->message }}
                                                        </a>
                                                    @else
                                                        {{ $user->name }}

                                                        <br>
                                                        {{ $lastMessage->getExcept() }}
                                                    @endif
                                                @endif
                                                @if ($lastMessage->bestand)
                                                    @if (str_contains($lastMessage->bestand, 'mp4'))
                                                        <video width="120" height="120"
                                                            class="rounded ms-2 mt-1" controls>
                                                            <source class=""
                                                                src="/storage/app/public/{{ $lastMessage->bestand }}"
                                                                type="video/mp4">
                                                            Your browser does not support HTML video.
                                                        </video>
                                                    @elseif(str_contains($lastMessage->bestand, 'mp3'))
                                                        <hr>
                                                        <audio class="mt-4 ps-2" controls>
                                                            <source
                                                                src="/storage/app/public/{{ $lastMessage->bestand }}"
                                                                type="audio/ogg">
                                                            <source
                                                                src="/storage/app/public/{{ $lastMessage->bestand }}"
                                                                type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    @else
                                                        <img class="rounded border ms-3 mt-1"
                                                            style="width: 120px;height:120px;color:blue"
                                                            src="/storage/app/public/{{ $lastMessage->bestand }}">
                                                    @endif
                                                @endif
                                            </h5>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach

                    </div>
                    <!--End Front Page-->

            </div>
            @endif


        </div>

</div>
</section>
<!-- End Chat Section -->
<div wire:loading wire:target="getUser,saveMessage,resetMessage,deleteMessage,delete" class="bg-transparent"
    id="preloader"></div>

</div>
