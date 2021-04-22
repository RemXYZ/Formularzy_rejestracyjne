function setCSS (el,mix,arg2) {
	if (typeof mix == "object") {
		for (const [key, value] of Object.entries(mix)) {
			el.style[key] = value;
		}
	}else if (typeof mix == "string" && typeof arg2 == "string") {
		el.style[mix] = arg2;
	}
}
function send_reg(e,EL) {
let but = this;
// but = EL;
//INPUT PART
const inps = {
	"name":{},
	"email":{},
	"pass":{},
	"accept":{}
};
for (let [key,v] of Object.entries(but.form.elements)){
	if (v.tagName == "INPUT") {
		if (inps[v.name] !== undefined) {
			inps[v.name] = {"el":v,"v":v.value};
		}else {
			console.error("Input "+v.name+" has incorrect name, or the total number of inputs must be 4, add the given name to the object 'inps'")
		}
	}
}

//ALERT PART
//CHAKING PART

var stop = false;
const msgs = {
	"name":{
		"el":"",
		check:function(inp) {
			this.el.innerHTML = "";

		}
	},

	"email":{
		"el":"",
		check:function(inp) {
			this.el.innerHTML = "";
			const regex = /^\S+@\S+$/;
			if (inp.value == "" || !regex.test(String(inp.value))) {
				this.el.innerHTML = "Niepoprawny adres e-mail";
				setCSS(this.el,"display","block");
				setCSS(this.el,"color","red");
				return false;
			}

			return true;
		}
	},

	"pass":{
		"el":"",
		check:function(inp) {
			this.el.innerHTML = "";
			let regex1 = /[A-Z]/;
			let regex2 = /[0-9]/;
			let regex3 = /[a-z]/;
			let error = false;
			if (!regex3.test(inp.value)) {
				this.el.innerHTML = "Proszę wpisać co najmniej jedną małą literę.";
				error = true;
			}
			if (!regex1.test(inp.value)) {
				this.el.innerHTML = "Proszę wpisać co najmniej jedną wielką literę.";
				error = true;
			}
			if (!regex2.test(inp.value)) {
				this.el.innerHTML = "Proszę wpisać co najmniej jedną cyfrę";
				error = true;
			}
			if (inp.value.length<=8) {
				this.el.innerHTML = "Proszę wpisać co najmniej 8 znaków.";
				error = true;
			}
			if (error) {
				setCSS(this.el,"display","block");
				setCSS(this.el,"color","red");
				return false;
			}else {
				return true;
			}
		}
	},

	"accept":{
		"el":"",
		check:function(inp) {
			this.el.innerHTML = "";

			if (!inp.checked) {
				this.el.innerHTML = "Zaakceptuj warunki korzystania z usługi";
				setCSS(this.el,"display","block");
				setCSS(this.el,"color","red");
				return false;
			}

			return true;
		}
	}
}

//ADDING INPUT TO THE OBJECT
for (let v of Object.values(but.form.querySelectorAll(".error"))) {
	let val = v.classList[0].replace("_msg","");
	if (msgs[val] !== undefined) {
		msgs[val].el = v;
	}else {
		console.error("Span '"+v.classList[0]+"' has incorrect class name, or the total number of inputs must be 4, add the given class to the object 'msgs'");
	}
}


for (let [key,v] of Object.entries(msgs)) {
v.check(inps[key].el);
}

if (stop) {
	// e.preventDefault();
}

//END OF FUNCTION
}
//END OF FUNCTION