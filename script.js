const allFilterBtn = document.getElementById('allFilterBtn');
const allFilterPanel = document.getElementById('allFilterPanel');
const allSortBtn = document.getElementById('allSortBtn');
const allSortMenu = document.getElementById('allSortMenu');
const allTopicPython = document.getElementById('allTopicPython');
const allCourseContainer = document.getElementById('allCourseContainer');

allFilterBtn.addEventListener('click', () => {
    allFilterPanel.classList.toggle('active');
    allSortMenu.classList.remove('active');
});

document.addEventListener('click', (e) => {
    const clickedInsideFilter = allFilterPanel.contains(e.target) || allFilterBtn.contains(e.target);
    const clickedInsideSort = allSortMenu.contains(e.target) || allSortBtn.contains(e.target);
    if (!clickedInsideFilter) allFilterPanel.classList.remove('active');
    if (!clickedInsideSort) allSortMenu.classList.remove('active');
});

// ✅ Filter logic
allTopicPython.addEventListener('change', () => {
    const showOnlyPython = allTopicPython.checked;
    const cards = document.querySelectorAll('.all-course-card');

    cards.forEach(card => {
        const category = card.dataset.category.toLowerCase();
        if (showOnlyPython) {
            // show only python courses
            card.style.display = category === 'python' ? 'flex' : 'none';
        } else {
            // reset all
            card.style.display = 'flex';
        }
    });

    allFilterPanel.classList.remove('active');
});

allTopicWebdev.addEventListener('change', () => {
    const showOnlyWebdev = allTopicWebdev.checked;
    const cards = document.querySelectorAll('.all-course-card');

    cards.forEach(card => {
        const category = card.dataset.category.toLowerCase();
        if (showOnlyWebdev) {
            // show only webdev courses
            card.style.display = category === 'webdev' ? 'flex' : 'none';
        } else {
            // reset all
            card.style.display = 'flex';
        }
    });

    allFilterPanel.classList.remove('active');
});

allTopicDs.addEventListener('change', () => {
    const showOnlyDs = allTopicDs.checked;
    const cards = document.querySelectorAll('.all-course-card');

    cards.forEach(card => {
        const category = card.dataset.category.toLowerCase();
        if (showOnlyDs) {
            // show only ds courses
            card.style.display = category === 'ds' ? 'flex' : 'none';
        } else {
            // reset all
            card.style.display = 'flex';
        }
    });

    allFilterPanel.classList.remove('active');
});



allSortBtn.addEventListener('click', () => {
    allSortMenu.classList.toggle('active');
    allFilterPanel.classList.remove('active');
});

// ✅ Sorting logic
allSortMenu.addEventListener('click', (e) => {
    const item = e.target.closest('.all-sort-item');
    if (!item) return;
    const type = item.dataset.sort;

    const cards = Array.from(document.querySelectorAll('.all-course-card'));

    if (type === 'rating') {
        cards.sort((a, b) => parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating));
    } else if (type === 'popular') {
        cards.sort((a, b) => parseInt(b.dataset.popularity) - parseInt(a.dataset.popularity));
    } else if (type === 'newest') {
        cards.sort((a, b) => new Date(b.dataset.date) - new Date(a.dataset.date));
    }

    cards.forEach(c => allCourseContainer.appendChild(c));
    allSortMenu.classList.remove('active');
});

// course section 
 const courses = [
      { name: "Java Tutorial for Complete Beginners", url: "web-dev.html" },
      { name: "The Complete Python Bootcamp From Zero to Hero in Python", url: "data-science.html" },
      { name: "100 Days of Code: The Complete Python Pro Bootcamp", url: "java.html" },
      { name: "The Complete Full-Stack Web Development Bootcamp", url: "react.html" },
      { name: "Database Design Basics", url: "database.html" }
    ];

    const searchBox = document.getElementById("searchBox");
    const dropdown = document.getElementById("dropdown");

    searchBox.addEventListener("input", function() {
      const query = this.value.toLowerCase();
      dropdown.innerHTML = "";
      if (query.length === 0) {
        dropdown.style.display = "none";
        return;
      }

      const filtered = courses.filter(course => 
        course.name.toLowerCase().includes(query)
      );

      if (filtered.length > 0) {
        dropdown.style.display = "block";
        filtered.forEach(course => {
          const option = document.createElement("div");
          option.textContent = course.name;
          option.onclick = () => { window.location.href = course.url; };
          dropdown.appendChild(option);
        });
      } else {
        dropdown.style.display = "none";
      }
    });

    // Hide dropdown when clicking outside
    document.addEventListener("click", function(e) {
      if (!e.target.closest(".search-container")) {
        dropdown.style.display = "none";
      }
    });