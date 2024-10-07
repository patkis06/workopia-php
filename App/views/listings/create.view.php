<?= load_partial('header') ?>
<?= load_partial('navbar') ?>
<?= load_partial('showcase-search') ?>
<?= load_partial('top-banner') ?>

<!-- Post a Job Form Box -->
<section class="flex justify-center items-center mt-20">
  <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-600 mx-6">
    <h2 class="text-4xl text-center font-bold mb-4">Create Job Listing</h2>
    <!-- <div class="message bg-red-100 p-3 my-3">This is an error message.</div>
        <div class="message bg-green-100 p-3 my-3">
          This is a success message.
        </div> -->
    <form method="POST" action="/listings">
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
        Job Info
      </h2>
      <?= load_partial('error', ['errors' => $errors ?? []]) ?>
      <div class="mb-4">
        <input
          type="text"
          name="title"
          value="<?= $data['title'] ?? '' ?>"
          placeholder="Job Title"
          class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <div class="mb-4">
        <textarea
          name="description"
          placeholder="Job Description"
          class="w-full px-4 py-2 border rounded focus:outline-none"><?= $data['description'] ?? '' ?></textarea>
      </div>
      <div class="mb-4">
        <input
          type="text"
          name="salary"
          value="<?= $data['salary'] ?? '' ?>"
          placeholder="Annual Salary"
          class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <div class="mb-4">
        <input
          type="text"
          name="requirments"
          value="<?= $data['requirments'] ?? '' ?>"
          placeholder="Requirements"
          class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <div class="mb-4">
        <input
          type="text"
          name="benefits"
          value="<?= $data['benefits'] ?? '' ?>"
          placeholder="Benefits"
          class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <div class="mb-4">
        <input
          type="text"
          name="tags"
          value="<?= $data['tags'] ?? '' ?>"
          placeholder="Tags"
          class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
        Company Info & Location
      </h2>
      <div class="mb-4">
        <input
          type="text"
          name="company"
          value="<?= $data['company'] ?? '' ?>"
          placeholder="Company Name"
          class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <div class="mb-4">
        <input
          type="text"
          name="address"
          value="<?= $data['address'] ?? '' ?>"
          placeholder="Address"
          class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <div class="mb-4">
        <input
          type="text"
          name="city"
          value="<?= $data['city'] ?? '' ?>"
          placeholder="City"
          class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <div class="mb-4">
        <input
          type="text"
          name="state"
          value="<?= $data['state'] ?? '' ?>"
          placeholder="State"
          class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <div class="mb-4">
        <input
          type="text"
          name="phone"
          value="<?= $data['phone'] ?? '' ?>"
          placeholder="Phone"
          class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <div class="mb-4">
        <input
          type="email"
          name="email"
          value="<?= $data['email'] ?? '' ?>"
          placeholder="Email Address For Applications"
          class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <button
        class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none">
        Save
      </button>
      <a
        href="/"
        class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none">
        Cancel
      </a>
    </form>
  </div>
</section>

<?= load_partial('bottom-banner') ?>
<?= load_partial('footer') ?>