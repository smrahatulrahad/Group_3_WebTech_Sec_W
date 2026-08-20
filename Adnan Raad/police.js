let currentFilter = "all";


function searchPosts() {

    let search = document.getElementById("searchInput").value.toLowerCase();

    let posts = document.querySelectorAll(".post-card");

    let found = 0;


    posts.forEach(function(post) {

        let text = post.innerText.toLowerCase();

        let status = post.dataset.status;

        let emergency = post.dataset.emergency;

        let show = false;


        if (currentFilter == "all") {

            show = true;

        }

        else if (currentFilter == "Emergency") {

            show = emergency == "true";

        }

        else {

            show = status == currentFilter;

        }


        if (text.includes(search) && show) {

            post.style.display = "block";

            found++;

        }

        else {

            post.style.display = "none";

        }

    });


    let noResult = document.getElementById("noResult");


    if (found == 0) {

        noResult.style.display = "block";

    }

    else {

        noResult.style.display = "none";

    }

}


function filterPosts(filter) {

    currentFilter = filter;


    let buttons = document.querySelectorAll(".filters button");


    buttons.forEach(function(button) {

        button.classList.remove("active");

    });


    if (filter == "all") {

        document.getElementById("allBtn").classList.add("active");

    }

    else if (filter == "Open") {

        document.getElementById("openBtn").classList.add("active");

    }

    else if (filter == "In Progress") {

        document.getElementById("progressBtn").classList.add("active");

    }

    else if (filter == "Resolved") {

        document.getElementById("resolvedBtn").classList.add("active");

    }

    else if (filter == "Emergency") {

        document.getElementById("emergencyBtn").classList.add("active");

    }


    searchPosts();
}


function toggleCase(button) {

    let post = button.closest(".post-card");

    let status = post.querySelector(".case-status");

    let resolveButton = post.querySelector(".resolve-btn");

    let taken = post.dataset.taken;


    if (taken == "other") {

        let answer = confirm(
            "This case is currently taken by another officer. Do you want to take this case?"
        );

        if (answer == false) {
            return;
        }

    }


    if (taken == "me") {

        let answer = confirm("Do you want to release this case?");

        if (answer == false) {
            return;
        }


        post.dataset.taken = "";

        post.dataset.status = "Open";


        button.innerText = "Take Case";


        status.innerText = "Open";

        status.className = "case-status open";


        resolveButton.disabled = false;

    }

    else {

        let answer = confirm("Do you want to take this case?");

        if (answer == false) {
            return;
        }


        post.dataset.taken = "me";

        post.dataset.status = "In Progress";


        button.innerText = "Release Case";


        status.innerText = "In Progress (You)";

        status.className = "case-status progress";


        resolveButton.disabled = false;

    }


    searchPosts();
}


function toggleResolved(button) {

    if (button.disabled == true) {
        return;
    }


    let post = button.closest(".post-card");

    let status = post.querySelector(".case-status");


    if (post.dataset.status == "Resolved") {

        let answer = confirm(
            "Do you want to unmark this case as resolved?"
        );

        if (answer == false) {
            return;
        }


        if (post.dataset.taken == "me") {

            post.dataset.status = "In Progress";

            status.innerText = "In Progress (You)";

            status.className = "case-status progress";

        }

        else {

            post.dataset.status = "Open";

            status.innerText = "Open";

            status.className = "case-status open";

        }


        button.innerText = "Mark Resolved";

    }

    else {

        let answer = confirm(
            "Do you want to mark this case as resolved?"
        );

        if (answer == false) {
            return;
        }


        post.dataset.status = "Resolved";

        status.innerText = "Resolved";

        status.className = "case-status resolved";


        button.innerText = "Unmark Resolved";

    }


    searchPosts();
}


function refreshPage() {

    document.getElementById("searchInput").value = "";

    currentFilter = "all";

    filterPosts("all");
}


document.getElementById("searchInput").addEventListener("keydown", function(event) {

    if (event.key == "Enter") {
        searchPosts();
    }

});


filterPosts("all");