/*
*@Author Odain Chevannes
*@Date 11-24-2015
*/
(function(){

	var button =  document.getElementById("button");
	button.addEventListener("click",
		function(event){
			var form = document.forms[0];
			var email = document.getElementById("email");
			var years = document.getElementById("years");
			var pass1 = document.getElementById("passwordFirst");
			var pass2 = document.getElementById("passwordSecond");

			checkEmpty(form,event);
			validateEmail(email,event);
			checkInt(years,event);
			comparePassword(pass1,pass2,event);
	});

	//clear the incorrect field and set the field red
	function makeError(x,e){
		e.preventDefault();
		x.value = "";
		x.style.backgroundColor = "red";
		clearFormat(x);
	}
	function clearFormat(i){
		i.addEventListener("click",function(event){
			i.style.backgroundColor = "white";
		})
	}
	function validateEmail(a,b){
		/*
		validate email
		*/
		var t = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i;
		var email = document.getElementById("email");
		var chars = email.value.split("");
			
		// should only be one @ character
		var at_only_once = email.value.split("@").length == 2;
		//dot comes after @ and somthing comes after the @ (there can be more than one dot)
		var in_order = (chars.indexOf(".")>chars.indexOf("@")) && email.value.split(".").length > 1;
		//***client-side verification is pretty much unreliable as JavaScript can be disabled on that side

		//check
		if(!at_only_once || !t.test(email.value) || !in_order){
			makeError(a,b);
		}
	}
	function checkEmpty(f,e){
		/*
		Check the other fields for missing information
		*/
		var i;
		for(i = 0; i < form.length; i++){
			if(f.elements[i].value == "" || f.elements[i].value == null){
				makeError(f.elements[i],e);
			}
		}
	}
	function comparePassword(p1,p2,e){
		if(!(p1.value==p2.value)){
			makeError(p1,e);
			makeError(p2,e);
		}
	}
	function checkInt(value,e){
		var psudo = parseInt(value.value);
		//any non number will give a NaN
		//floats will work, hence the number of is compared with its floor()
		if(psudo==NaN || Math.floor(value.value)!=value.value){ makeError(value,e);}
	}
})();
