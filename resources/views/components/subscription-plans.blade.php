<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Subscription Plans</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="p-6 border rounded-lg shadow bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 hover:shadow-xl transition">
            <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Free Plan</h3>
            <p class="text-gray-700 dark:text-gray-300">Basic editing tools, limited storage, and watermarked images.</p>
            <ul class="mt-4 list-disc pl-6 text-gray-700 dark:text-gray-300">
                <li>Basic features</li>
                <li>Limited storage</li>
                <li>Watermarks</li>
            </ul>
            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 mt-4"
                    :disabled="activePlan === 'Free'">Current Plan</button>
        </div>
        <div class="p-6 border rounded-lg shadow bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 hover:shadow-xl transition">
            <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Pro Plan</h3>
            <p class="text-gray-700 dark:text-gray-300">Advanced AI tools, unlimited storage, and no watermarks.</p>
            <ul class="mt-4 list-disc pl-6 text-gray-700 dark:text-gray-300">
                <li>Advanced AI tools</li>
                <li>Unlimited storage</li>
                <li>No watermarks</li>
            </ul>
            <form action="{{ route('subscriptions.upgrade') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 mt-4"
                        :disabled="activePlan === 'Pro'">{{ auth()->user()->subscription && auth()->user()->subscription->plan_name === 'Pro' ? 'Current Plan' : 'Upgrade to Pro' }}</button>
            </form>
        </div>
    </div>
</div>