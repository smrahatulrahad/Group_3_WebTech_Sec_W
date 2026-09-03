let currentFilter = "all";


/* =========================
   SEARCH POSTS
========================= */

function searchPosts() {

    let searchValue =
        document
            .getElementById("searchInput")
            .value
            .toLowerCase()
            .trim();


    let posts =
        document.querySelectorAll(
            ".post-card"
        );


    let found = 0;


    posts.forEach(function (post) {

        let text =
            post.innerText
                .toLowerCase();


        let status =
            post.dataset.status;


        let emergency =
            post.dataset.emergency;


        let filterMatch = false;


        /* All */

        if (currentFilter == "all") {

            filterMatch = true;

        }


        /* Emergency */

        else if (
            currentFilter == "Emergency"
        ) {

            if (emergency == "true") {

                filterMatch = true;

            }

        }


        /* Status */

        else {

            if (
                status == currentFilter
            ) {

                filterMatch = true;

            }

        }


        /* Search text */

        let searchMatch =
            text.includes(searchValue);


        if (
            filterMatch &&
            searchMatch
        ) {

            post.style.display =
                "block";

            found++;

        }
        else {

            post.style.display =
                "none";

        }

    });



    /* No result message */

    let noResult =
        document.getElementById(
            "noResult"
        );


    if (found == 0) {

        noResult.style.display =
            "block";

    }
    else {

        noResult.style.display =
            "none";

    }

}



/* =========================
   FILTER POSTS
========================= */

function filterPosts(filter) {

    currentFilter = filter;


    /* Remove active button */

    let filterButtons =
        document.querySelectorAll(
            ".filters button"
        );


    filterButtons.forEach(
        function (button) {

            button.classList.remove(
                "active"
            );

        }
    );



    /* Set selected button */

    if (filter == "all") {

        document
            .getElementById("allBtn")
            .classList.add("active");

    }


    else if (filter == "Open") {

        document
            .getElementById("openBtn")
            .classList.add("active");

    }


    else if (
        filter == "In Progress"
    ) {

        document
            .getElementById(
                "progressBtn"
            )
            .classList.add("active");

    }


    else if (
        filter == "Resolved"
    ) {

        document
            .getElementById(
                "resolvedBtn"
            )
            .classList.add("active");

    }


    else if (
        filter == "Emergency"
    ) {

        document
            .getElementById(
                "emergencyBtn"
            )
            .classList.add("active");

    }


    searchPosts();

}



/* =========================
   REFRESH
========================= */

function refreshPage() {

    document
        .getElementById(
            "searchInput"
        )
        .value = "";


    currentFilter = "all";


    filterPosts("all");

}



/* =========================
   ENTER KEY SEARCH
========================= */

document
    .getElementById(
        "searchInput"
    )
    .addEventListener(
        "keydown",
        function (event) {

            if (
                event.key == "Enter"
            ) {

                event.preventDefault();

                searchPosts();

            }

        }
    );



/* =========================
   INITIAL LOAD
========================= */

filterPosts("all");