function citizen() {
    document.getElementById("selectedRole").value = "citizen";
    document.getElementById("citizenFields").style.display = "block";
    document.getElementById("policeFields").style.display = "none";
    document.getElementById("journalistFields").style.display = "none";
}

function police() {
    document.getElementById("selectedRole").value = "police";
    document.getElementById("citizenFields").style.display = "none";
    document.getElementById("policeFields").style.display = "block";
    document.getElementById("journalistFields").style.display = "none";
}

function journalist() {
    document.getElementById("selectedRole").value = "journalist";
    document.getElementById("citizenFields").style.display = "none";
    document.getElementById("policeFields").style.display = "none";
    document.getElementById("journalistFields").style.display = "block";
}