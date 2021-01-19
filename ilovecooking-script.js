//Wait for jQuery to load
jQuery(document).ready(function ($) {
    if (jQuery("body").hasClass("logged-in")) {
      $("#header-cookbook").first().removeClass("hide");
    } else {
      $("#header-login").first().removeClass("hide");
    }
  
    const bookmark = async (link, id, action = "add") => {
      fetch(link + "?wpfpaction=" + action + "&postid=" + id + "&ajax=1")
        .then((response) => {
          if (response.status === 200) {
            const items = document.querySelectorAll("#bkmk-" + id);
            if (action === "add") {
              items.forEach((item) => item.classList.add("active"));
            } else {
              items.forEach((item) => item.classList.remove("active"));
            }
            return;
          }
          // You must be logged in
          throw new Error("You must be logged in");
        })
        .catch((err) => console.error(err));
    };
  
    window.addEventListener("load", async function () {
      if (typeof Splide === "function") {
        // Initiate Splide libarary
        new Splide(".splide", {
          type: "loop",
          perPage: 1,
          rewind: true,
          autoplay: true,
          pauseOnHover: true,
        }).mount();
      }
  
      // Convert svg images to inline svgs for better control
      const images = jQuery("img.svg");
      let ajaxCounter = 0;
  
      images.each(function () {
        let $img = jQuery(this);
        let imgID = $img.attr("id");
        let imgClass = $img.attr("class");
        let imgURL = $img.attr("src");
        let imgPostId = $img.attr("data-post-id");
        let imgLink = $img.attr("data-link");
  
        jQuery.get(
          imgURL,
          function (data) {
            // Get the SVG tag, ignore the rest
            let $svg = jQuery(data).find("svg");
  
            // Add replaced image's ID to the new SVG
            if (typeof imgID !== "undefined") {
              $svg = $svg.attr("id", imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== "undefined") {
              $svg = $svg.attr("class", imgClass + " replaced-svg");
            }
  
            if (typeof imgPostId !== "undefined") {
              $svg = $svg.attr("data-post-id", imgPostId);
            }
  
            // console.log(imgPostId, imgLink)
            $svg.on("click", function () {
              console.log(this);
              if (this.classList.contains("active")) {
                // console.log("removing")
                bookmark(imgLink, imgPostId, "remove");
              } else {
                // console.log("adding")
                bookmark(imgLink, imgPostId);
              }
            });
  
            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr("xmlns:a");
  
            // Replace image with new SVG
            $img.replaceWith($svg);
            ajaxCounter = ajaxCounter + 1;
            if (ajaxCounter === images.length) {
              // Mark active items
              ilcPostIds().forEach(async (post_id) => {
                const items = document.querySelectorAll(
                  'svg[data-post-id="' + post_id + '"]'
                );
                console.log("post id", post_id);
                if (items) {
                  items.forEach((item) => item.classList.add("active"));
                }
              });
            }
          },
          "xml"
        );
      });
    });
  });
  