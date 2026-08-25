function citizen() {

    document.getElementById("selectedRole").value = "citizen";

    document.getElementById("citizenFields").classList.remove("hidden");

    document.getElementById("policeFields").classList.add("hidden");

    document.getElementById("journalistFields").classList.add("hidden");
}



function police() {

    document.getElementById("selectedRole").value = "police";

    document.getElementById("citizenFields").classList.add("hidden");

    document.getElementById("policeFields").classList.remove("hidden");

    document.getElementById("journalistFields").classList.add("hidden");
}



function journalist() {

    document.getElementById("selectedRole").value = "journalist";

    document.getElementById("citizenFields").classList.add("hidden");

    document.getElementById("policeFields").classList.add("hidden");

    document.getElementById("journalistFields").classList.remove("hidden");
}



/* Registration validation */
function validateRegistration() {

    let fullname = document.getElementById("fullname").value.trim();

    let email = document.getElementById("email").value.trim();

    let password = document.getElementById("password").value;

    let phone = document.getElementById("phone").value.trim();

    let role = document.getElementById("selectedRole").value;


    if (fullname == "") {

        alert("Full name is required.");

        return false;
    }


    if (email == "") {

        alert("Email address is required.");

        return false;
    }


    if (password.length < 6) {

        alert("Password must be at least 6 characters.");

        return false;
    }


    if (phone == "") {

        alert("Phone number is required.");

        return false;
    }


    if (isNaN(phone)) {

        alert("Phone number must contain numbers only.");

        return false;
    }



    if (role == "citizen") {

        if (
            document.getElementById("district").value.trim() == "" ||
            document.getElementById("upazila").value.trim() == "" ||
            document.getElementById("union").value.trim() == "" ||
            document.getElementById("area").value.trim() == "" ||
            document.getElementById("nid").value.trim() == ""
        ) {

            alert("Please complete all Citizen information.");

            return false;
        }

    }



    if (role == "police") {

        if (
            document.getElementById("rank").value.trim() == "" ||
            document.getElementById("station").value.trim() == "" ||
            document.getElementById("badge").value.trim() == ""
        ) {

            alert("Please complete all Police information.");

            return false;
        }

    }



    if (role == "journalist") {

        if (
            document.getElementById("channel").value.trim() == "" ||
            document.getElementById("journalist_id").value.trim() == ""
        ) {

            alert("Please complete all Journalist information.");

            return false;
        }

    }



    if (
        document.getElementById("q1").value.trim() == "" ||
        document.getElementById("q2").value.trim() == "" ||
        document.getElementById("q3").value.trim() == ""
    ) {

        alert("Please answer all security questions.");

        return false;
    }


    return true;
}



/* Citizen opens first */
window.onload = function () {

    citizen();

};