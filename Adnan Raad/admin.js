let currentView = "all";


function searchPosts() {

    let search = document.getElementById("searchInput").value.toLowerCase();

    let posts = document.querySelectorAll(".post-card");


    posts.forEach(function(post) {

        let text = post.innerText.toLowerCase();

        let deleted = post.dataset.deleted == "true";

        let show = false;


        if (currentView == "all") {
            show = !deleted;
        }
        else {
            show = deleted;
        }


        if (text.includes(search) && show) {
            post.style.display = "block";
        }
        else {
            post.style.display = "none";
        }

    });

}


function showAll() {

    currentView = "all";

    document.getElementById("allButton").classList.add("active");

    document.getElementById("trashButton").classList.remove("active");

    searchPosts();
}


function showTrash() {

    currentView = "trash";

    document.getElementById("trashButton").classList.add("active");

    document.getElementById("allButton").classList.remove("active");

    searchPosts();
}


function refreshPage() {

    document.getElementById("searchInput").value = "";

    currentView = "all";

    showAll();
}


function deletePost(button) {

    let answer = confirm("Move this post to trash?");

    if (answer == false) {
        return;
    }


    let post = button.closest(".post-card");

    post.dataset.deleted = "true";


    button.innerText = "Restore";

    button.className = "restore-btn";

    button.setAttribute("onclick", "restorePost(this)");


    let statusArea = post.querySelector(".status-area");

    let trash = document.createElement("span");

    trash.className = "status deleted";

    trash.innerText = "Trash";

    statusArea.appendChild(trash);


    searchPosts();
}


function restorePost(button) {

    let answer = confirm("Restore this post?");

    if (answer == false) {
        return;
    }


    let post = button.closest(".post-card");

    post.dataset.deleted = "false";


    button.innerText = "Delete";

    button.className = "delete-btn";

    button.setAttribute("onclick", "deletePost(this)");


    let trash = post.querySelector(".deleted");

    if (trash != null) {
        trash.remove();
    }


    searchPosts();
}


document.getElementById("searchInput").addEventListener("keydown", function(event) {

    if (event.key == "Enter") {
        searchPosts();
    }

});


showAll();