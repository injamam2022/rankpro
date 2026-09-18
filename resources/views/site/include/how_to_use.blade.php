@php
    $video_tutorial = \App\Models\Video_tutorial::where('page_key',request()->path())->where('status',1)->first();
    $video_tutorial_link = "";
    if($video_tutorial){
        $video_tutorial_link = $video_tutorial->video_link;
    }
@endphp

@if($video_tutorial_link)
    <!-- How to Use -->
    <div class="howToUse"  onclick="openModal();">
        <img src="{{ asset('') }}web/images/howToUse.png" class="img-fluid howToUse_img" alt="">
    </div>
@endif

<div class="howToUse_video">
    <!-- MODAL -->
    <div class="modal" id="videoModal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">✕</span>
    
            <iframe
                id="videoFrame"
                src=""
                allow="autoplay; fullscreen"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</div>




<script>
    function openAlert(){

    }

    function openModal() {
        const modal = document.getElementById("videoModal");
        const frame = document.getElementById("videoFrame");
    
        frame.src = "{{$video_tutorial_link}}";
        modal.style.display = "flex";
    }
    
    function closeModal() {
        const modal = document.getElementById("videoModal");
        const frame = document.getElementById("videoFrame");
    
        frame.src = "";
        modal.style.display = "none";
    }
    
    // CLOSE ON OUTSIDE CLICK
    window.onclick = function(e) {
        const modal = document.getElementById("videoModal");
        if (e.target === modal) {
            closeModal();
        }
    };

</script>