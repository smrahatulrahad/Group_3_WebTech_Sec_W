/* =====================================
   SEARCH POSTS
===================================== */

function searchPosts() {

    const searchValue =
        document
            .getElementById("searchInput")
            .value
            .toLowerCase()
            .trim();


    const filter =
        document
            .getElementById("filterSelect")
            .value;


    const posts =
        document.querySelectorAll(
            ".post-card"
        );


    let visibleCount = 0;


    posts.forEach(function(post) {

        const text =
            post.innerText
                .toLowerCase();


        const covered =
            post.dataset.covered === "true";


        let filterMatch = true;


        /* =========================
           COVERED FILTER
        ========================= */

        if (filter === "covered") {

            filterMatch = covered;

        }


        /* =========================
           UNCOVERED FILTER
        ========================= */

        else if (filter === "uncovered") {

            filterMatch = !covered;

        }


        /* =========================
           SEARCH
        ========================= */

        const searchMatch =
            text.includes(searchValue);


        if (
            filterMatch &&
            searchMatch
        ) {

            post.style.display =
                "block";

            visibleCount++;

        }
        else {

            post.style.display =
                "none";

        }

    });


    updateResultCount(
        visibleCount
    );

}



/* =====================================
   FILTER POSTS
===================================== */

function filterPosts() {

    searchPosts();

}



/* =====================================
   REFRESH
===================================== */

function refreshPage() {

    document
        .getElementById(
            "searchInput"
        )
        .value = "";


    document
        .getElementById(
            "filterSelect"
        )
        .value = "all";


    searchPosts();

}



/* =====================================
   UPDATE POST COUNT
===================================== */

function updateResultCount(count) {

    const postCount =
        document.getElementById(
            "postCount"
        );


    if (count === 1) {

        postCount.innerText =
            "1 post";

    }
    else {

        postCount.innerText =
            count + " posts";

    }


    const noResult =
        document.getElementById(
            "noResult"
        );


    if (count === 0) {

        noResult.style.display =
            "block";

    }
    else {

        noResult.style.display =
            "none";

    }

}



/* =====================================
   ENTER KEY SEARCH
===================================== */

document
    .getElementById(
        "searchInput"
    )
    .addEventListener(
        "keydown",
        function(event) {

            if (
                event.key === "Enter"
            ) {

                event.preventDefault();

                searchPosts();

            }

        }
    );



/* =====================================
   INITIAL LOAD
===================================== */

searchPosts();