<template>
  <div class="reviews-section bg-white p-6 rounded-lg shadow">
    <!-- Review Form -->
    <div v-if="isLoggedIn" class="review-form mb-8">
      <h3 class="text-xl font-semibold mb-4">Tulis Review</h3>
      <div class="space-y-4">
        <div>
          <label class="block text-gray-700 mb-2">Rating</label>
          <div class="flex space-x-2">
            <button
              v-for="star in 5"
              :key="star"
              @click="newReview.rating = star"
              class="text-2xl focus:outline-none"
              :class="star <= newReview.rating ? 'text-yellow-400' : 'text-gray-300'"
            >
              ★
            </button>
          </div>
        </div>

        <div>
          <label class="block text-gray-700 mb-2">Komentar</label>
          <textarea
            v-model="newReview.comment"
            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            rows="4"
          ></textarea>
        </div>

        <div>
          <label class="block text-gray-700 mb-2">Foto Produk (Opsional)</label>
          <input
            type="file"
            @change="handlePhotoUpload"
            multiple
            accept="image/*"
            class="w-full p-2 border rounded-lg"
          >
        </div>

        <div>
          <label class="block text-gray-700 mb-2">Video Review (Opsional)</label>
          <input
            type="file"
            @change="handleVideoUpload"
            accept="video/*"
            class="w-full p-2 border rounded-lg"
          >
        </div>

        <button
          @click="submitReview"
          class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
          :disabled="isSubmitting"
        >
          {{ isSubmitting ? 'Mengirim...' : 'Kirim Review' }}
        </button>
      </div>
    </div>

    <!-- Reviews Filters and Sorting -->
    <div class="reviews-list">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold">Reviews ({{ filteredReviews.length }})</h3>
        <div class="flex space-x-4">
          <select v-model="filterRating" @change="applyFilters" class="border rounded px-3 py-1">
            <option value="">All Ratings</option>
            <option value="5">5 Stars</option>
            <option value="4">4 Stars</option>
            <option value="3">3 Stars</option>
            <option value="2">2 Stars</option>
            <option value="1">1 Star</option>
          </select>
          <select v-model="filterWithPhotos" @change="applyFilters" class="border rounded px-3 py-1">
            <option value="">All Reviews</option>
            <option value="true">With Photos</option>
          </select>
          <select v-model="sortBy" @change="applyFilters" class="border rounded px-3 py-1">
            <option value="newest">Newest</option>
            <option value="oldest">Oldest</option>
            <option value="highest">Highest Rating</option>
            <option value="lowest">Lowest Rating</option>
            <option value="helpful">Most Helpful</option>
          </select>
        </div>
      </div>
      <div v-if="filteredReviews.length === 0" class="text-gray-500">
        Belum ada review untuk produk ini.
      </div>
      <div v-else class="space-y-6">
        <div v-for="review in filteredReviews" :key="review.id" class="review-item border-b pb-6">
          <div class="flex items-start justify-between">
            <div class="flex items-center space-x-4">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                  <span class="text-xl">{{ review.user.name.charAt(0) }}</span>
                </div>
              </div>
              <div class="flex-1">
                <div class="flex items-center space-x-2">
                  <h4 class="font-semibold">{{ review.user.name }}</h4>
                  <span v-if="review.is_verified_purchase" class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                    ✓ Verified Purchase
                  </span>
                </div>
                <div class="text-yellow-400">
                  {{ '★'.repeat(review.rating) }}
                  <span class="text-gray-300">{{ '★'.repeat(5 - review.rating) }}</span>
                </div>
                <p class="text-gray-600 mt-2">{{ review.comment }}</p>
              </div>
            </div>
            <span class="text-gray-400 text-sm">
              {{ formatDate(review.created_at) }}
            </span>
          </div>

          <!-- Review Photos -->
          <div v-if="review.photos && review.photos.length" class="mt-4 flex space-x-2 overflow-x-auto">
            <img
              v-for="(photo, index) in review.photos"
              :key="index"
              :src="getPhotoUrl(photo)"
              class="w-24 h-24 object-cover rounded"
              @click="openPhotoViewer(review.photos, index)"
            >
          </div>

          <!-- Review Video -->
          <div v-if="review.video" class="mt-4">
            <video
              :src="getVideoUrl(review.video)"
              controls
              class="max-w-full h-auto rounded"
            ></video>
          </div>

          <!-- Review Reply -->
          <div v-if="review.reply" class="mt-4 p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-2 mb-2">
              <span class="font-semibold text-gray-700">Seller Reply</span>
              <span class="text-gray-400 text-sm">{{ formatDate(review.replied_at) }}</span>
            </div>
            <p class="text-gray-700">{{ review.reply }}</p>
          </div>

          <!-- Helpful Button -->
          <div class="mt-4 flex items-center justify-between">
            <button
              v-if="isLoggedIn"
              @click="markHelpful(review)"
              :disabled="review.userVotedHelpful"
              class="text-blue-600 hover:text-blue-800 text-sm"
              :class="{ 'text-gray-400 cursor-not-allowed': review.userVotedHelpful }"
            >
              👍 Helpful ({{ review.helpful_count || 0 }})
            </button>
            <span class="text-gray-400 text-sm">
              {{ formatDate(review.created_at) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';

export default {
  name: 'ProductReview',
  props: {
    productId: {
      type: Number,
      required: true
    }
  },

  setup(props) {
    const reviews = ref([]);
    const filteredReviews = ref([]);
    const isSubmitting = ref(false);
    const toast = useToast();
    const isLoggedIn = ref(false); // Ganti dengan state management yang sesuai

    const filterRating = ref('');
    const filterWithPhotos = ref('');
    const sortBy = ref('newest');

    const newReview = ref({
      rating: 0,
      comment: '',
      photos: [],
      video: null
    });

    const fetchReviews = async () => {
      try {
        const response = await axios.get(`/api/reviews?product_id=${props.productId}`);
        // Perbaiki akses data
        reviews.value = response.data.data || response.data;
        applyFilters();
      } catch (error) {
        console.error('Error fetching reviews:', error);
        toast.error('Gagal memuat review');
      }
    };

    const applyFilters = () => {
      let filtered = [...reviews.value];

      // Filter by rating
      if (filterRating.value) {
        filtered = filtered.filter(review => review.rating == filterRating.value);
      }

      // Filter by photos
      if (filterWithPhotos.value === 'true') {
        filtered = filtered.filter(review => review.photos && review.photos.length > 0);
      }

      // Sort
      filtered.sort((a, b) => {
        switch (sortBy.value) {
          case 'oldest':
            return new Date(a.created_at) - new Date(b.created_at);
          case 'highest':
            return b.rating - a.rating;
          case 'lowest':
            return a.rating - b.rating;
          case 'helpful':
            return (b.helpful_count || 0) - (a.helpful_count || 0);
          case 'newest':
          default:
            return new Date(b.created_at) - new Date(a.created_at);
        }
      });

      filteredReviews.value = filtered;
    };

    const markHelpful = async (review) => {
      if (review.userVotedHelpful) return;

      try {
        await axios.post(`/api/reviews/${review.id}/helpful`);
        review.helpful_count = (review.helpful_count || 0) + 1;
        review.userVotedHelpful = true;
        toast.success('Marked as helpful');
      } catch (error) {
        toast.error('Failed to mark as helpful');
      }
    };

    const handlePhotoUpload = (event) => {
      newReview.value.photos = Array.from(event.target.files);
    };

    const handleVideoUpload = (event) => {
      newReview.value.video = event.target.files[0];
    };

    const submitReview = async () => {
      if (!newReview.value.rating || !newReview.value.comment) {
        toast.error('Rating dan komentar harus diisi');
        return;
      }

      isSubmitting.value = true;
      const formData = new FormData();
      formData.append('id_produk', props.productId);
      formData.append('rating', newReview.value.rating);
      formData.append('comment', newReview.value.comment);

      newReview.value.photos.forEach(photo => {
        formData.append('photos[]', photo);
      });

      if (newReview.value.video) {
        formData.append('video', newReview.value.video);
      }

      try {
        await axios.post('/api/reviews', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        toast.success('Review berhasil ditambahkan');
        newReview.value = { rating: 0, comment: '', photos: [], video: null };
        await fetchReviews();
      } catch (error) {
        toast.error('Gagal menambahkan review');
      } finally {
        isSubmitting.value = false;
      }
    };

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    };

    const getPhotoUrl = (photo) => {
      return `/storage/${photo}`;
    };

    const getVideoUrl = (video) => {
      return `/storage/${video}`;
    };

    onMounted(() => {
      fetchReviews();
    });

    return {
      reviews,
      filteredReviews,
      newReview,
      isSubmitting,
      isLoggedIn,
      filterRating,
      filterWithPhotos,
      sortBy,
      handlePhotoUpload,
      handleVideoUpload,
      submitReview,
      formatDate,
      getPhotoUrl,
      getVideoUrl,
      applyFilters,
      markHelpful
    };
  }
};
</script>
