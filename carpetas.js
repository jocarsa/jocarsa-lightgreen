document.addEventListener("DOMContentLoaded", function() {
  // Process all folder headers
  const folders = document.querySelectorAll("nav li.folder-header");
  
  folders.forEach(function(folder) {
    const nestedUl = folder.querySelector("ul");
    const iconSpan = folder.querySelector(".folder-icon");
    const folderId = folder.dataset.folderId;
    
    // Make sure the nested UL has an explicit height when expanded.
    // We set it to "auto" after the animation ends.
    function setExpandedHeight() {
      nestedUl.style.height = nestedUl.scrollHeight + "px";
      // After the transition, clear the inline height so it can adjust naturally.
      nestedUl.addEventListener("transitionend", function handler() {
        // Only clear height if the folder is still expanded
        if (!nestedUl.classList.contains("collapsed")) {
          nestedUl.style.height = "auto";
        }
        nestedUl.removeEventListener("transitionend", handler);
      });
    }
    
    // Check stored state (default: unfolded)
    const storedState = localStorage.getItem("folderState-" + folderId);
    if (storedState === "folded") {
      // Start collapsed: force height 0.
      nestedUl.style.height = "0px";
      nestedUl.classList.add("collapsed");
      if (iconSpan) iconSpan.textContent = "📁";
    } else {
      // Expanded: set height to auto (and to the current scrollHeight so next collapse works)
      nestedUl.style.height = nestedUl.scrollHeight + "px";
      // After a brief delay, remove inline style so that it becomes fluid.
      setTimeout(function() {
        nestedUl.style.height = "auto";
      }, 300);
      if (iconSpan) iconSpan.textContent = "📂";
    }
    
    // Click listener on folder header (but not on links)
    folder.addEventListener("click", function(e) {
      if (e.target.tagName === "A") return;
      
      if (nestedUl.classList.contains("collapsed")) {
        // Expand folder
        nestedUl.classList.remove("collapsed");
        // Set height to 0px so we can animate
        nestedUl.style.height = "0px";
        // Force reflow
        nestedUl.offsetHeight;
        // Then animate to its full scrollHeight
        setExpandedHeight();
        if (iconSpan) iconSpan.textContent = "📂";
        localStorage.setItem("folderState-" + folderId, "unfolded");
      } else {
        // Collapse folder
        // Set an explicit height (its current computed height) so we can animate from it.
        nestedUl.style.height = nestedUl.scrollHeight + "px";
        // Force reflow
        nestedUl.offsetHeight;
        // Then set height to 0 for collapse.
        nestedUl.style.height = "0px";
        nestedUl.classList.add("collapsed");
        if (iconSpan) iconSpan.textContent = "📁";
        localStorage.setItem("folderState-" + folderId, "folded");
      }
      
      e.stopPropagation();
    });
  });
});
