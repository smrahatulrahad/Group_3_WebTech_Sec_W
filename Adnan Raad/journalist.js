function searchPosts() {

    let search = document.getElementById("searchInput").value.toLowerCase();

    let filter = document.getElementById("filterSelect").value;

    let posts = document.querySelectorAll(".post-card");

    let count = 0;


    posts.forEach(function(post) {

        let text = post.innerText.toLowerCase();

        let covered = post.dataset.covered == "true";

        let show = true;


        if (filter == "covered") {
            show = covered;
        }

        else if (filter == "uncovered") {
            show = !covered;
        }


        if (text.includes(search) && show) {

            post.style.display = "block";

            count++;

        }

        else {

            post.style.display = "none";

        }

    });


    updateResultCount(count);
}


function filterPosts() {

    searchPosts();
}


function toggleCoverage(button) {

    let post = button.closest(".post-card");

    let covered = post.dataset.covered == "true";

    let tagArea = post.querySelector(".tag-area");


    if (covered == true) {

        let answer = confirm("Do you want to uncover this post?");

        if (answer == false) {
            return;
        }


        post.dataset.covered = "false";

        button.innerText = "Cover This Post";

        button.classList.remove("covered-button");


        let tag = tagArea.querySelector(".covered-tag");

        if (tag != null) {
            tag.remove();
        }

    }

    else {

        let answer = confirm("Do you want to cover this post?");

        if (answer == false) {
            return;
        }


        post.dataset.covered = "true";

        button.innerText = "Uncover";

        button.classList.add("covered-button");


        let tag = document.createElement("span");

        tag.className = "covered-tag";

        tag.innerText = "Covered by You";


        let emergencyTag = tagArea.querySelector(".emergency-tag");


        if (emergencyTag != null) {

            tagArea.insertBefore(tag, emergencyTag);

        }

        else {

            tagArea.appendChild(tag);

        }

    }


    searchPosts();
}


function playVideo() {

    alert("Video will play here after connecting the uploaded video.");
}


function refreshPage() {

    document.getElementById("searchInput").value = "";

    document.getElementById("filterSelect").value = "all";

    searchPosts();
}


function updateResultCount(count) {

    let postCount = document.getElementById("postCount");

    let noResult = document.getElementById("noResult");


    if (count == 1) {

        postCount.innerText = "1 post";

    }

    else {

        postCount.innerText = count + " posts";

    }


    if (count == 0) {

        noResult.style.display = "block";

    }

    else {

        noResult.style.display = "none";

    }

}


document.getElementById("searchInput").addEventListener("keydown", function(event) {

    if (event.key == "Enter") {
        searchPosts();
    }

});


searchPosts();