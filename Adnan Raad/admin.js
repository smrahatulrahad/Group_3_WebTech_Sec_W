let currentView = "all";


function searchPosts() {

    const search =
        document
        .getElementById("searchInput")
        .value
        .toLowerCase();

    const posts =
        document.querySelectorAll(".post-card");


    posts.forEach(function(post) {

        const text =
            post.innerText.toLowerCase();

        const deleted =
            post.dataset.deleted === "true";


        let correctView = false;


        if (currentView === "all") {

            correctView = !deleted;

        }
        else {

            correctView = deleted;

        }


        if (
            text.includes(search) &&
            correctView
        ) {

            post.style.display = "block";

        }
        else {

            post.style.display = "none";

        }

    });

}



function showAll() {

    currentView = "all";


    document
        .getElementById("allButton")
        .classList.add("active");


    document
        .getElementById("trashButton")
        .classList.remove("active");


    searchPosts();

}



function showTrash() {

    currentView = "trash";


    document
        .getElementById("trashButton")
        .classList.add("active");


    document
        .getElementById("allButton")
        .classList.remove("active");


    searchPosts();

}



function refreshPage() {

    document
        .getElementById("searchInput")
        .value = "";

    currentView = "all";


    showAll();

}



function deletePost(button) {

    const answer =
        confirm("Move this post to trash?");


    if (!answer) {
        return;
    }


    const post =
        button.closest(".post-card");


    post.dataset.deleted = "true";


    button.innerText = "Restore";

    button.className = "restore-btn";

    button.setAttribute(
        "onclick",
        "restorePost(this)"
    );


    // Add trash label

    const statusArea =
        post.querySelector(".status-area");


    if (!post.querySelector(".deleted")) {

        const trash =
            document.createElement("span");

        trash.className =
            "status deleted";

        trash.innerText =
            "Trash";

        statusArea.appendChild(trash);

    }


    searchPosts();

}



function restorePost(button) {

    const answer =
        confirm("Restore this post?");


    if (!answer) {
        return;
    }


    const post =
        button.closest(".post-card");


    post.dataset.deleted = "false";


    button.innerText =
        "Delete";


    button.className =
        "delete-btn";


    button.setAttribute(
        "onclick",
        "deletePost(this)"
    );


    const trash =
        post.querySelector(".deleted");


    if (trash) {

        trash.remove();

    }


    searchPosts();

}


/* Press Enter for search */

document
    .getElementById("searchInput")
    .addEventListener(
        "keydown",
        function(event) {

            if (event.key === "Enter") {

                searchPosts();

            }

        }
    );


/* Initial view */

showAll();