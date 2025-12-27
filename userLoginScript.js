let cart = [];

// Function to add item with image + link
function addToCart(courseTitle, price, imgUrl, courseUrl) {
    cart.push({ title: courseTitle, price: price, image: imgUrl, link: courseUrl });
    renderCart();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
}

// Render cart items
function renderCart() {
    const cartItems = document.getElementById("cart-items");
    const cartCount = document.getElementById("cart-count");

    cartCount.innerText = cart.length;
    cartItems.innerHTML = "";

    if (cart.length === 0) {
        cartItems.innerHTML = "<p>No items in cart</p>";
        return;
    }

    cart.forEach((item, index) => {
        let div = document.createElement("div");
        div.style.display = "flex";
        div.style.alignItems = "center";
        div.style.borderBottom = "1px solid #ddd";
        div.style.padding = "5px";
        div.innerHTML = `
            <img src="${item.image}" width="40" height="40" style="margin-right:8px; border-radius:5px;">
            <div style="flex:1;">
                <a href="${item.link}" style="text-decoration:none; color:#333;">
                    <strong>${item.title}</strong>
                </a><br>
                ₹${item.price}
            </div>
            <button onclick="removeFromCart(${index})" style="color:red; background:none; border:none; cursor:pointer;">✖</button>
        `;
        cartItems.appendChild(div);
    });
}

function addToCart(courseTitle, price, imgUrl, courseUrl) {
    cart.push({ title: courseTitle, price: price, image: imgUrl, link: courseUrl });
    renderCart();
    alert("Great Buddy, " + courseTitle + " has been added to your cart!");
}

//sorting data

document.getElementById("filterCategory").addEventListener("change", function () {
    let selected = this.value.toLowerCase();
    let cards = document.querySelectorAll(".all-course-card");

    cards.forEach(card => {
        let category = card.getAttribute("data-category").toLowerCase();

        if (selected === "all" || category === selected) {
            card.style.display = "flex";
        } else {
            card.style.display = "none";
        }
    });
});

const select = document.getElementById("filterCategory");

// add default option
select.innerHTML = `<option value="all">All</option>`;

// gather unique categories
let categories = new Set();
document.querySelectorAll(".all-course-card").forEach(card => {
    categories.add(card.getAttribute("data-category"));
});

// add dynamically
categories.forEach(cat => {
    select.innerHTML += `<option value="${cat}">${cat}</option>`;
});

// filter logic
select.addEventListener("change", function () {
    let selected = this.value.toLowerCase();
    let cards = document.querySelectorAll(".all-course-card");

    cards.forEach(card => {
        let category = card.getAttribute("data-category").toLowerCase();

        card.style.display = (selected === "all" || category === selected)
            ? "flex"
            : "none";
    });
});

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