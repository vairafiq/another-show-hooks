jQuery( function($){
	// show hide the hook flotting window
	$(".ash-show-hooks-icon-test").click(function(){
		$(".ash-nested-hooks-block").toggleClass("ash_show");
		$(".ash-nested-hooks-block").addClass("ash_transition_fix");
		$(".ash-nested-hooks-block").toggleClass("ash_slider-fix");
		$(".ash-show-hooks-icon-test").toggleClass("ash_icon-fix");
	  });

	$(".ash-show-move-window").mouseover(function(){
		$("#ash-dragable-hook-panel").removeClass("ash_transition_fix");
	  });

	$("#disable_callback_function").click(function(){
		$(".ash-tab-box-div").toggleClass("ash_padding_fix");
	});


    const wrapper = document.querySelector("#ash-dragable-hook-panel");
    var header = '';
    if( wrapper !== null ) {
      header = wrapper.querySelector(".ash-show-move-window");
    }

    function onDrag({movementX, movementY}){
      let getStyle = window.getComputedStyle(wrapper);
      let leftVal = parseInt(getStyle.left);
      let topVal = parseInt(getStyle.top);
      wrapper.style.left = `${leftVal + movementX}px`;
      wrapper.style.top = `${topVal + movementY}px`;
    }

    if( header ) {
      header.addEventListener("mousedown", ()=>{
        header.classList.add("ash_active");
        header.addEventListener("mousemove", onDrag);
      });
  
      document.addEventListener("mouseup", ()=>{
        header.classList.remove("ash_active");
        header.removeEventListener("mousemove", onDrag);
      });
    }
});