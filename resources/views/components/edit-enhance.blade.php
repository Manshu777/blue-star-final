<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Edit & Enhance</h2>
    <div x-data="editData()">
        <div class="edit-tools grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-lg">
                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">Basic Tools</h3>
                <div class="flex flex-wrap justify-center">
                    <button type="button"
                            class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500">Crop</button>
                    <button type="button"
                            class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                            @click="rotate(90)">Rotate 90°</button>
                    <button type="button"
                            class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                            @click="rotate(-90)">Rotate -90°</button>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-lg">
                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">Advanced AI Tools <span class="pro-badge">Pro</span></h3>
                <div class="flex flex-wrap justify-center">
                    <button type="button"
                            class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                            @click="sharpen">Sharpen (AI)</button>
                    <button type="button"
                            class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                            @click="colorCorrect">Color Correct (AI)</button>
                </div>
            </div>
        </div>
        <canvas id="edit-canvas"
                class="preview-canvas mx-auto mt-4 border border-gray-300 dark:border-gray-600 rounded-lg"></canvas>
    </div>
</div>