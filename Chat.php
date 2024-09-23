<?php

namespace App\Livewire;

use App\Models\Friend;
use App\Models\Melding;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;

class Chat extends Component
{
    public $sendMessage = false;
    public $sender;
    public $senderId;
    public $senderName;
    public $senderImage;
    public $recipient;
    public $recipientId;
    public $recipientName;
    public $recipientImage;
    public $recipientPhoto;
    public $recipientEmail;
    public $message;
    #[Validate('min:3', message: 'Text is te kort')]
    public $feed;
    public $friend;
    public $friendName;
    public $friendId;
    public $messageId;
    public $search = "";
    public $bestand;
    public $friendExist;
    public $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~','{','}','[',']','*','|','/',];
    public $videoStream = false;
    protected $paginationTheme = 'bootstrap';
    use WithFileUploads;
    use WithPagination, WithoutUrlPagination;

    public function render(): View
    {
        $meldingen = Melding::where('recipient_id',Auth::user()->id)->latest()->get();
        $feeds = Message::where('recipient_id',null)->latest()->get();
        $messages = Message::where('user_id',Auth::user()->id)->latest()->get();
        $loggedIn = DB::table('sessions')->where('user_id',$this->recipientId)->first();

        $users = User::WithLastMessageFromCurrentUser()
        ->orderBy('name','asc')->get();
        $searchUsers = User::where('name', 'like', '%' . $this->search . '%')
        ->orWhere('email', 'like', '%' . $this->search . '%')->orderBy('name','asc')->get();
        return view('livewire.chat', compact('users','messages','feeds','meldingen','loggedIn','searchUsers'));
    }
    public function getVideoStream()
    {
        $this->videoStream = ! $this->videoStream;
    }
    public function getUser($id)
    {
        $this->sendMessage =  true;
        $user = Auth::user();
        $this->sender = $user;
        $this->senderName = $user->name;
        $this->senderId = $user->id;
        $this->senderImage = $user->profile_photo_url;
        $recipient = User::find($id);
        $this->recipientId = $recipient->id;
        $this->recipientName = $recipient->name;
        $this->recipientImage = $recipient->profile_photo_url;
        $this->recipientPhoto = $recipient->profile_photo_path;
        $this->recipientEmail = $recipient->email;
        $this->reset('search');
    }
    public function saveMessage($id)
    {
        $this->recipient = User::find($id);

        $this->saveFirstMessage();

        $user = Auth::user();
        $this->senderName = $user->name;
        $this->senderId = $user->id;

        $this->saveSecondMessage($id);

        $this->saveMelding($id);

        $this->reset('message', 'bestand');
        $this->getUser($id);
    }
    private function saveFirstMessage()
    {
        $message = new Message();
        $message->message_id = $this->recipient->id;
        $message->user_id = $this->recipient->id;
        $message->sender_id = $this->senderId;
        $message->name = $this->senderName;
        $message->recipient_id = $this->recipientId;
        if ($this->message) {
            $message->message = $this->message;
        }
        if ($this->bestand) {
            $image = $this->bestand->store('chat', 'public');
            $message->bestand = $image;
        }
        $message->save();
    }
    private function saveSecondMessage($id)
    {
        $message = new Message();
        $message->message_id = $id;
        $message->user_id = Auth::user()->id;
        $message->sender_id = Auth::user()->id;
        $message->name = $this->recipientName;
        $message->recipient_id = $this->recipientId;
        if ($this->message) {
            $message->message = $this->message;
        }
        if ($this->bestand) {
            $image = $this->bestand->store('chat', 'public');
            $message->bestand = $image;
        }
        $message->save();
        $this->messageId = $message->id;
    }
    private function saveMelding($id)
    {
        $melding = new Melding();
        $melding->message_id = $id;
        $melding->sender_id = Auth::user()->id;
        $melding->user_id = Auth::user()->id;
        $melding->name = Auth::user()->name;
        $melding->recipient_id = $this->recipientId;
        if($this->message){
            $melding->message = $this->message;
        }
        if ($this->bestand) {
            $image = $this->bestand->store('chat', 'public');
            $melding->bestand = $image;
        }
        $melding->save();
    }

    public function resetBestand(){
        $this->reset('bestand');
    }
    public function resetMessage(){
        $this->reset();
    }
    public function deleteMessage(User $user)
    {
        $user = Auth::user();
        $sent = Message::where('user_id', $user->id)
            ->where('recipient_id', $this->recipientId);
        $sent->delete();

        $received = Message::where('user_id', $user->id)
            ->where('recipient_id', $user->id)
            ->where('sender_id', $this->recipientId)
            ->where('message_id', $user->id);
        $received->delete();

        $meldingen = Melding::where('sender_id', $this->recipientId)->where('recipient_id', $user->id);
        $meldingen->delete();

        session()->flash('succes', 'Berichten zijn verwijderd');
    }
    public function delete($id)
    {
        $message = Message::find($id);
        $message->delete();
        $user = Auth::user();
        $meldingen = Melding::where('sender_id', $this->recipientId)->where('recipient_id', $user->id);
        $meldingen->delete();
    }

}
