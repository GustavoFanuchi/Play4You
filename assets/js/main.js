// Mobile menu toggle
function toggleMobileMenu() {
  const menu = document.getElementById("mobile-menu")
  menu.classList.toggle("hidden")
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault()
    const target = document.querySelector(this.getAttribute("href"))
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start",
      })
    }
  })
})

// Add animation classes on scroll
const observerOptions = {
  threshold: 0.1,
  rootMargin: "0px 0px -50px 0px",
}

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add("animate-slide-up")
    }
  })
}, observerOptions)

// Observe elements with animation classes
document.addEventListener("DOMContentLoaded", () => {
  const animatedElements = document.querySelectorAll(".animate-on-scroll")
  animatedElements.forEach((el) => observer.observe(el))
})

// Form validation helpers
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

function validatePassword(password) {
  return password.length >= 6
}

// Show/hide password functionality
function togglePassword(inputId, iconId) {
  const input = document.getElementById(inputId)
  const icon = document.getElementById(iconId)

  if (input.type === "password") {
    input.type = "text"
    icon.classList.remove("fa-eye")
    icon.classList.add("fa-eye-slash")
  } else {
    input.type = "password"
    icon.classList.remove("fa-eye-slash")
    icon.classList.add("fa-eye")
  }
}

// Toast notifications
function showToast(message, type = "info") {
  const toast = document.createElement("div")
  toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`

  const colors = {
    success: "bg-green-500 text-white",
    error: "bg-red-500 text-white",
    warning: "bg-yellow-500 text-white",
    info: "bg-blue-500 text-white",
  }

  toast.className += ` ${colors[type] || colors.info}`
  toast.textContent = message

  document.body.appendChild(toast)

  // Animate in
  setTimeout(() => {
    toast.classList.remove("translate-x-full")
  }, 100)

  // Remove after 3 seconds
  setTimeout(() => {
    toast.classList.add("translate-x-full")
    setTimeout(() => {
      document.body.removeChild(toast)
    }, 300)
  }, 3000)
}

// Advanced form validation and user experience enhancements
// Advanced form validation
function validateForm(formId) {
  const form = document.getElementById(formId)
  if (!form) return false

  const inputs = form.querySelectorAll("input[required], select[required], textarea[required]")
  let isValid = true

  inputs.forEach((input) => {
    const errorElement = input.parentNode.querySelector(".form-error")

    // Remove existing error
    if (errorElement) {
      errorElement.remove()
    }

    // Validate input
    let error = ""
    if (!input.value.trim()) {
      error = "Este campo é obrigatório"
    } else if (input.type === "email" && !validateEmail(input.value)) {
      error = "Email inválido"
    } else if (input.type === "password" && !validatePassword(input.value)) {
      error = "Senha deve ter pelo menos 6 caracteres"
    } else if (input.name === "confirm_password") {
      const password = form.querySelector('input[name="password"]')
      if (password && input.value !== password.value) {
        error = "Senhas não coincidem"
      }
    } else if ((input.name === "phone" || input.id === "phone") && !validatePhoneNumber(input.value)) {
      error = "Telefone deve ter o formato (XX) XXXXX-XXXX"
    }

    if (error) {
      isValid = false
      input.classList.add("border-red-500")
      const errorDiv = document.createElement("div")
      errorDiv.className = "form-error text-red-500 text-sm mt-1"
      errorDiv.textContent = error
      input.parentNode.appendChild(errorDiv)
    } else {
      input.classList.remove("border-red-500")
      input.classList.add("border-green-500")
    }
  })

  return isValid
}

// Real-time search functionality
function initializeSearch() {
  const searchInput = document.getElementById("search-input")
  const searchResults = document.getElementById("search-results")

  if (!searchInput) return

  let searchTimeout

  searchInput.addEventListener("input", function () {
    clearTimeout(searchTimeout)
    const query = this.value.trim()

    if (query.length < 2) {
      if (searchResults) searchResults.innerHTML = ""
      return
    }

    searchTimeout = setTimeout(() => {
      performSearch(query)
    }, 300)
  })
}

function performSearch(query) {
  const searchResults = document.getElementById("search-results")
  if (!searchResults) return

  // Show loading
  searchResults.innerHTML = '<div class="p-4 text-center"><div class="spinner mx-auto"></div></div>'

  fetch(`/api/search?q=${encodeURIComponent(query)}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.products && data.products.length > 0) {
        searchResults.innerHTML = data.products
          .map(
            (product) => `
          <div class="p-4 border-b hover:bg-gray-50 cursor-pointer" onclick="window.location.href='/product?id=${product.id}'">
            <div class="flex items-center space-x-3">
              <img src="${product.image}" alt="${product.name}" class="w-12 h-12 object-cover rounded">
              <div>
                <h4 class="font-medium">${product.name}</h4>
                <p class="text-sm text-gray-600">R$ ${product.price}/dia</p>
              </div>
            </div>
          </div>
        `,
          )
          .join("")
      } else {
        searchResults.innerHTML = '<div class="p-4 text-center text-gray-500">Nenhum produto encontrado</div>'
      }
    })
    .catch((error) => {
      console.error("Search error:", error)
      searchResults.innerHTML = '<div class="p-4 text-center text-red-500">Erro na busca</div>'
    })
}

// Image gallery functionality
function initializeGallery() {
  const galleryThumbs = document.querySelectorAll(".gallery-thumb")
  const mainImage = document.getElementById("main-image")

  galleryThumbs.forEach((thumb) => {
    thumb.addEventListener("click", function () {
      // Remove active class from all thumbs
      galleryThumbs.forEach((t) => t.classList.remove("ring-2", "ring-blue-500"))

      // Add active class to clicked thumb
      this.classList.add("ring-2", "ring-blue-500")

      // Update main image
      if (mainImage) {
        mainImage.src = this.src
        mainImage.alt = this.alt
      }
    })
  })
}

// Date picker functionality for rental dates
function initializeDatePicker() {
  const startDateInput = document.getElementById("start_date")
  const endDateInput = document.getElementById("end_date")

  if (!startDateInput || !endDateInput) return

  // Set minimum date to today
  const today = new Date().toISOString().split("T")[0]
  startDateInput.min = today
  endDateInput.min = today

  startDateInput.addEventListener("change", function () {
    const startDate = new Date(this.value)
    const minEndDate = new Date(startDate)
    minEndDate.setDate(minEndDate.getDate() + 1)

    endDateInput.min = minEndDate.toISOString().split("T")[0]

    if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
      endDateInput.value = minEndDate.toISOString().split("T")[0]
    }

    calculateRentalPrice()
  })

  endDateInput.addEventListener("change", calculateRentalPrice)
}

// Calculate rental price
function calculateRentalPrice() {
  const startDate = document.getElementById("start_date")?.value
  const endDate = document.getElementById("end_date")?.value
  const dailyPrice = Number.parseFloat(document.getElementById("daily-price")?.dataset.price || 0)
  const priceDisplay = document.getElementById("total-price")

  if (!startDate || !endDate || !dailyPrice) return

  const start = new Date(startDate)
  const end = new Date(endDate)
  const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24))

  if (days > 0) {
    const subtotal = days * dailyPrice
    const insurance = subtotal * 0.1 // 10% insurance
    const serviceFee = 15 // Fixed service fee
    const total = subtotal + insurance + serviceFee

    if (priceDisplay) {
      priceDisplay.innerHTML = `
        <div class="space-y-2">
          <div class="flex justify-between">
            <span>R$ ${dailyPrice.toFixed(2)} x ${days} dias</span>
            <span>R$ ${subtotal.toFixed(2)}</span>
          </div>
          <div class="flex justify-between">
            <span>Seguro</span>
            <span>R$ ${insurance.toFixed(2)}</span>
          </div>
          <div class="flex justify-between">
            <span>Taxa de serviço</span>
            <span>R$ ${serviceFee.toFixed(2)}</span>
          </div>
          <hr>
          <div class="flex justify-between font-bold text-lg">
            <span>Total</span>
            <span>R$ ${total.toFixed(2)}</span>
          </div>
        </div>
      `
    }
  }
}

// Chat functionality
function initializeChat() {
  const chatForm = document.getElementById("chat-form")
  const messagesContainer = document.getElementById("chat-messages")

  if (!chatForm || !messagesContainer) return

  chatForm.addEventListener("submit", function (e) {
    e.preventDefault()

    const messageInput = this.querySelector('input[name="message"]')
    const message = messageInput.value.trim()

    if (!message) return

    // Add message to UI immediately
    addMessageToChat(message, true)
    messageInput.value = ""

    // Send to server
    const formData = new FormData(this)
    fetch("/chat/send", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (!data.success) {
          showToast("Erro ao enviar mensagem", "error")
        }
      })
      .catch((error) => {
        console.error("Chat error:", error)
        showToast("Erro ao enviar mensagem", "error")
      })
  })

  // Chat polling is handled in chat page
}

function addMessageToChat(message, isOwn = false) {
  const messagesContainer = document.getElementById("chat-messages")
  if (!messagesContainer) return

  const messageDiv = document.createElement("div")
  messageDiv.className = `message ${isOwn ? "own" : ""} animate-fade-in-up`

  const now = new Date().toLocaleTimeString("pt-BR", {
    hour: "2-digit",
    minute: "2-digit",
  })

  messageDiv.innerHTML = `
    <div class="message-bubble">
      ${message}
      <div class="text-xs opacity-70 mt-1">${now}</div>
    </div>
  `

  messagesContainer.appendChild(messageDiv)
  messagesContainer.scrollTop = messagesContainer.scrollHeight
}



// Favorites functionality
function toggleFavorite(productId) {
  const heartIcon = document.getElementById(`heart-${productId}`)
  if (!heartIcon) return

  fetch("/api/toggle-favorite", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ product_id: productId }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        heartIcon.classList.toggle("text-red-500")
        heartIcon.classList.toggle("text-gray-400")
        showToast(data.favorited ? "Adicionado aos favoritos" : "Removido dos favoritos", "success")
      }
    })
    .catch((error) => {
      console.error("Favorite error:", error)
      showToast("Erro ao atualizar favoritos", "error")
    })
}

// Initialize all functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  initializeSearch()
  initializeGallery()
  initializeDatePicker()
  initializeChat()
  initializePhoneFormatting()

  // Add loading states to forms
  const forms = document.querySelectorAll("form")
  forms.forEach((form) => {
    form.addEventListener("submit", function () {
      const submitBtn = this.querySelector('button[type="submit"]')
      if (submitBtn) {
        submitBtn.disabled = true
        submitBtn.innerHTML = '<div class="spinner mr-2"></div>Enviando...'
      }
    })
  })
})

// Phone formatting function
function formatPhoneNumber(input) {
  // Remove all non-numeric characters
  let value = input.value.replace(/\D/g, '')
  
  // Limit to 11 digits (XX) XXXXX-XXXX
  if (value.length > 11) {
    value = value.substring(0, 11)
  }
  
  // Apply formatting based on length
  if (value.length <= 2) {
    input.value = value
  } else if (value.length <= 7) {
    input.value = `(${value.substring(0, 2)}) ${value.substring(2)}`
  } else {
    input.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`
  }
}

// Phone validation function
function validatePhoneNumber(phone) {
  // Remove all non-numeric characters
  const cleanPhone = phone.replace(/\D/g, '')
  // Check if it has exactly 10 or 11 digits
  return cleanPhone.length === 10 || cleanPhone.length === 11
}

// Initialize phone formatting for all phone inputs
function initializePhoneFormatting() {
  const phoneInputs = document.querySelectorAll('input[name="phone"], input[id="phone"], input[type="tel"]')
  
  phoneInputs.forEach(input => {
    // Format on input
    input.addEventListener('input', function() {
      formatPhoneNumber(this)
    })
    
    // Format on paste
    input.addEventListener('paste', function(e) {
      setTimeout(() => {
        formatPhoneNumber(this)
      }, 10)
    })
    
    // Format existing values on page load
    if (input.value) {
      formatPhoneNumber(input)
    }
  })
}

// Utility functions
function formatCurrency(value) {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
  }).format(value)
}

function formatDate(date) {
  return new Date(date).toLocaleDateString("pt-BR")
}

// Lazy loading for images
function initializeLazyLoading() {
  const images = document.querySelectorAll("img[data-src]")

  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target
        img.src = img.dataset.src
        img.classList.remove("loading-skeleton")
        observer.unobserve(img)
      }
    })
  })

  images.forEach((img) => imageObserver.observe(img))
}

// Initialize lazy loading
document.addEventListener("DOMContentLoaded", initializeLazyLoading)
