<footer class="footer-admin text-center py-4 mt-5">

    <p class="mb-1 fw-semibold text-white small">
      © {{ date('Y') }} Admin Batik Wistara
    </p>
    <small class="text-white-50">
      Dibuat dengan <i class="fa-solid fa-heart text-danger"></i> oleh Tim Developer Batik Wistara
    </small>
  </div>
</footer>

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
  .footer-admin {
    background: linear-gradient(135deg, #071739, #1b2a4a);
    color: #ffffff;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    box-shadow: 0 -2px 10px rgb(255, 255, 255);
  }

  .footer-admin a:hover {
    color: #f6b400 !important;
  }

  .footer-admin i {
    animation: pulse 1.5s infinite;
  }

  @keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.8; }
    50% { transform: scale(1.2); opacity: 1; }
  }
</style>
</body>
</html>
