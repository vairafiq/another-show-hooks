jQuery( function($){
	// show hide the hook flotting window
	$(".abc-show-hooks-icon-test").click(function(){
		$(".abc-nested-hooks-block").toggleClass("abc_show");
		$(".abc-nested-hooks-block").addClass("abc_transition_fix");
		$(".abc-nested-hooks-block").toggleClass("abc_slider-fix");
		$(".abc-show-hooks-icon-test").toggleClass("abc_icon-fix");
	  });

	$(".abc-show-move-window").mouseover(function(){
		$("#abc-dragable-hook-panel").removeClass("abc_transition_fix");
	  });

	$("#disable_callback_function").click(function(){
		$(".abc-tab-box-div").toggleClass("abc_padding_fix");
	});


	const wrapper = document.querySelector("#abc-dragable-hook-panel"),
    header = wrapper.querySelector(".abc-show-move-window");

    function onDrag({movementX, movementY}){
      let getStyle = window.getComputedStyle(wrapper);
      let leftVal = parseInt(getStyle.left);
      let topVal = parseInt(getStyle.top);
      wrapper.style.left = `${leftVal + movementX}px`;
      wrapper.style.top = `${topVal + movementY}px`;
    }

    header.addEventListener("mousedown", ()=>{
      header.classList.add("abc_active");
      header.addEventListener("mousemove", onDrag);
    });

    document.addEventListener("mouseup", ()=>{
      header.classList.remove("abc_active");
      header.removeEventListener("mousemove", onDrag);
    });
});