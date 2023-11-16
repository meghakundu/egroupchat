<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\AppLayout::class, [] + (isset($attributes) ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl justify-centers mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6  bg-white border-b border-gray-200">
                    Group ID : <?php echo e($group->id); ?><br />
                    Group name : <?php echo e($group->name); ?>


                </div>
            </div>
<div class="chat-popup" >
  <form action="/store" id="myForm" class="form-container" method="POST">
    <?php echo csrf_field(); ?>
   <i class="fa-solid fa-chalkboard-user"></i> <h3>Chat with <?php echo e($group->name); ?></h3>
    <input type="hidden" name="sender_id" value="<?php echo e(Auth::user()->id); ?>"/>
   <?php if($sender_details!=null): ?>
    <?php $__currentLoopData = $sender_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div id="message" class="message" style="background:beige;min-width:200px;border:transparent;border-radius:12px;">
 <span style="color:black;float:left;">
 <?php echo e($item['eMessageData']['name']); ?><br>
 <?php echo e($item['text_message']); ?>

 </span> <br/><br>
 <?php echo e($item['created_at']); ?>

</div>
<br>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
    <textarea placeholder="Type message.." id="output" name="text_message"  rows='3' cols='50' required></textarea>
    <span class="microphone mt-2"> <i class="fa fa-microphone"></i><span class="recording-icon"></span></span>
    <input type="hidden" name="group_id" value="<?php echo e($group->id); ?>"/>
    <input type="submit" class="btn btn-primary" onsubmit="return false;" value="Send"/>
  </form>
</div>
            <div x-data="{ users: [], currentUserId : '',  emojis : [ '💪', '👀', '🥳', '😍', '🥰', '😎', '😂', '🤗' ]}"
                x-init="init" id="notification">

                <!-- <div class="mb-3 xl:w-96">
                    <div class="flex justify-center">
                        <div class="mb-3 xl:w-96">
                            <select @change="changeState($event.target.value)"
                                class="form-select appearance-noneblock w-20 px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none">
                                <option></option>
                                <template x-for="emoji in emojis">
                                    <option x-text="emoji"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div> -->

                <a href="<?php echo e(route('dashboard')); ?>"
                    class="inline-flex items-center px-4 py-2 mb-8 mt-8 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">leave
                </a>
                <ul
                    class="w-48 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    <template x-for="user in users">
                        <li class="py-2 px-4 w-full rounded-t-lg border-b border-gray-200 dark:border-gray-600">
                                  -
                            <strong x-text="user.name"></strong>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>


    <script src="<?php echo e(asset('js/app.js')); ?>"></script>

    <script type="text/javascript">
    function init() {
       
        this.currentUserId = "<?php echo e(auth()->user()->id); ?>";
        
        channel = Echo.join(`presence.<?php echo e($group->id); ?>`);
       
        this.changeState = (state) => {

            channel
                .whisper('changeState', {
                    state,
                    userId: this.currentUserId
                });


        }
        channel
            .here((users) => {
               this.users = users;
               console.log('123');
            })
            .joining((user) => {
               this.users.push(user);
               console.log('join'+user.name)
            })
            .leaving((user) => {
               this.users.splice(this.users.indexOf(user), 1);
               console.log('leaving'+user.name);
            })
            .error((error) => {
                console.log(error);
            })
            .listenForWhisper('changeState', (e) => {

                this.users.map(user => {
                    if (user.id == e.userId) {
                        user.state = e.state
                    }
                })

            });
    }
    
    var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
    var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList;
    var grammar = '#JSGF V1.0';
    var recognition = new SpeechRecognition();
    var speechRecognitionList = new SpeechGrammarList();
    speechRecognitionList.addFromString(grammar,1);
    recognition.grammars = speechRecognitionList;
    recognition.interimResults = false;
    recognition.onresult = function(event){
        var lastResult = event.results.length-1;
        var content = event.results[lastResult][0].transcript;
        console.log(content);
        document.getElementById('output').value=content;
        document.getElementById('myForm').submit();
    }
    recognition.onspeechend=function(){
        recognition.stop();
    }
    recognition.onerror = function(event){
        console.log(event.error);
        const microphone = document.querySelector('.microphone');
        microphone.classList.remove('recording');
    }
    document.querySelector('.microphone').addEventListener('click',function(){
        recognition.start();
        const microphone=document.querySelector('.microphone');
        microphone.classList.add('recording');
    });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?><?php /**PATH E:\xampp\htdocs\egroupchat\resources\views/group.blade.php ENDPATH**/ ?>