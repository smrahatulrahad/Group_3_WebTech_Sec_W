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


/* Citizen opens first */

window.onload = function () {

    citizen();

};