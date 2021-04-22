function setCSS (el,mix,arg2) {
	if (typeof mix == "object") {
		for (const [key, value] of Object.entries(mix)) {
			el.style[key] = value;
		}
	}else if (typeof mix == "string" && typeof arg2 == "string") {
		el.style[mix] = arg2;
	}
}
class Send_reg {
constructor(but) {
this.e = "";

if (but.tagName == "FORM") {
	this.myForm = but;
	console.log(this.myForm)
	for (let el of this.myForm.elements) {
		if (el.matches('button[type="submit"]')) {
			this.regBut = el;
    	}
  	}
}else {
	this.regBut = but;
	this.myForm = this.regBut.form;
}
this.inps = {
	"name":{},
	"email":{},
	"pass":{},
	"accept":{},
	"isW":false
};

this.add_inps();

}
//INPUT PART
add_inps (e,EL) {
if (e === undefined) {
	e = event;
}
for (let [key,v] of Object.entries(this.myForm.elements)){
	if (v.tagName == "INPUT" && !isNaN(key)) {
		//To po porostu sprawdza nazwe inputa i wpisuje element w juz przygotowany object, jesli znajdzie jakis nie istniejacy element, to wyswietli sie blad
		if (this.inps[v.name] !== undefined) {
			this.inps[v.name] = {"el":v,"v":v.value};
			this.inps.isW = true;
			if (v.changing === undefined) {
				v.changing = true;
				v.my_name = v.name;
				v.addEventListener("change",()=>{
				this.check_reg(event,v,0)});
			}
		}else {
			console.error("Input '"+v.name+"' has incorrect name, or the total number of inputs must be 4, add the given name to the object 'inps'");
			console.error(v);
			this.inps.isW = false;
		}
	}
}
}//END OF ADD INPS
//ALERT PART
//CHAKING PART
check_reg(e,$el,isBut) {
var stop_e = false;
const msgs = {
	"name":{
		"el":"",
		check:function(inp) {
		var error = false;
		if (this.el != "") {
			this.el.innerHTML = "";
			if (inp.value.length<=2) {
				this.el.innerHTML = "Proszę wpisać co najmniej 3 znaki.";
				error = true;
			}
		}
			if (error) {
				setCSS(this.el,"display","block");
				setCSS(this.el,"color","red");
				return true;
			}else {
				return false;
			}
		}
	},

	"email":{
		"el":"",
		check:function(inp) {
		var error = false;
		if (this.el != "") {
			this.el.innerHTML = "";
			const regex = /^\S+@\S+.\S+$/;
			if (inp.value == "" || !regex.test(String(inp.value))) {
				this.el.innerHTML = "Niepoprawny adres e-mail";
				error = true;
			}
		}
			if (error) {
				setCSS(this.el,"display","block");
				setCSS(this.el,"color","red");
				return true;
			}else {
				return false;
			}

			return false;
		}
	},

	"pass":{
		"el":"",
		check:function(inp) {
		var error = false;
		if (this.el != "") {
			this.el.innerHTML = "";
			let regex1 = /[A-Z]/;
			let regex2 = /[0-9]/;
			let regex3 = /[a-z]/;
			if (!regex3.test(inp.value)) {
				this.el.innerHTML = "Proszę wpisać co najmniej jedną małą literę.";
				error = true;
			}
			if (!regex1.test(inp.value)) {
				this.el.innerHTML = "Proszę wpisać co najmniej jedną wielką literę.";
				error = true;
			}
			if (!regex2.test(inp.value)) {
				this.el.innerHTML = "Proszę wpisać co najmniej jedną cyfrę.";
				error = true;
			}
			if (inp.value.length<=7) {
				this.el.innerHTML = "Proszę wpisać co najmniej 8 znaków.";
				error = true;
			}
		}
			if (error) {
				setCSS(this.el,"display","block");
				setCSS(this.el,"color","red");
				return true;
			}else {
				return false;
			}
		}
	},

	"accept":{
		"el":"",
		check:function(inp) {
		var error = false;
		if (this.el != "") {
			this.el.innerHTML = "";
			if (!inp.checked) {
				this.el.innerHTML = "Zaakceptuj warunki korzystania z usługi";
				setCSS(this.el,"display","block");
				setCSS(this.el,"color","red");
				return true;
			}
		}
			return false;
		}
	}
}

//ADDING INPUT TO THE OBJECT
for (let v of Object.values(this.myForm.querySelectorAll(".error"))) {
	let val = v.classList[0].replace("_msg","");
	//To po porostu sprawdza class spana i wpisuje element w juz przygotowany object, jesli znajdzie jakis nie istniejacy element, to wyswietli sie blad
	//NAZWA SPANA MUSI BYC TAKA SAMA JAK I INPUT
	if (msgs[val] !== undefined) {
		msgs[val].el = v;
	}else {
		console.error("Span '"+v.classList[0]+"' has incorrect class name, or the total number of inputs must be 4 = [name email pass accept], add the given class to the object 'msgs'");
	}
}

//CHAKING ALL INPUTS
//isBut is 1 if i click button [type="submit"]
if (isBut == 1) {
	for (let [key,v] of Object.entries(msgs)) {
		var rtn_stop_e = v.check(this.inps[key].el);
		if (rtn_stop_e) {
			stop_e = true;
		}
	}
}
//CHECKING ONE INPUT
if (isBut == 0) {
	//my name is name from object this.inps
	stop_e = msgs[$el.my_name].check($el);
}
if (stop_e) {
	e.preventDefault();
}
}//END OF CHECK REG

//END OF CLASS
}
//END OF CLASS