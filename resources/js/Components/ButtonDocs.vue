<script setup>
import { ref } from 'vue';
import Button from './Button.vue';
import Icon from './Icon.vue';

const loading = ref(false);
const showExamples = ref(true);

const simulateLoading = async () => {
    loading.value = true;
    await new Promise(resolve => setTimeout(resolve, 2000));
    loading.value = false;
};
</script>

<template>
    <div class="max-w-6xl mx-auto p-6 space-y-8">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Button Component Documentation</h1>
            <p class="text-gray-600">A comprehensive, reusable button component for the Biblioteca application</p>
        </div>

        <!-- Basic Variants -->
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Variants</h2>
            <div class="flex flex-wrap gap-4">
                <Button variant="primary">Primary</Button>
                <Button variant="secondary">Secondary</Button>
                <Button variant="success">Success</Button>
                <Button variant="danger">Danger</Button>
                <Button variant="outline">Outline</Button>
                <Button variant="neutral">Neutral</Button>
                <Button variant="ghost">Ghost</Button>
                <Button variant="link">Link</Button>
            </div>
        </section>

        <!-- Sizes -->
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Sizes</h2>
            <div class="flex flex-wrap items-center gap-4">
                <Button size="xs">Extra Small</Button>
                <Button size="sm">Small</Button>
                <Button size="md">Medium</Button>
                <Button size="lg">Large</Button>
                <Button size="xl">Extra Large</Button>
            </div>
        </section>        <!-- Icons -->
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Material Symbols Integration</h2>
            <div class="space-y-6">
                <!-- Material Symbols Icons -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Material Symbols (New!)</h3>
                    <div class="flex flex-wrap gap-4 mb-4">
                        <Button left-icon="search">Search Books</Button>
                        <Button left-icon="library_books" variant="secondary">My Library</Button>
                        <Button left-icon="favorite" variant="success">Add to Favorites</Button>
                        <Button left-icon="delete" variant="danger">Remove Book</Button>
                        <Button left-icon="edit" variant="outline">Edit Details</Button>
                        <Button left-icon="share" variant="ghost">Share Book</Button>
                    </div>
                </div>

                <!-- Icon Variants -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Icon Variants</h3>
                    <div class="flex flex-wrap gap-4 mb-4">
                        <Button left-icon="star" icon-variant="outlined">Outlined</Button>
                        <Button left-icon="star" icon-variant="rounded" variant="secondary">Rounded</Button>
                        <Button left-icon="star" icon-variant="sharp" variant="success">Sharp</Button>
                    </div>
                </div>

                <!-- Icon Weights and Fills -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Icon Customization</h3>
                    <div class="flex flex-wrap gap-4 mb-4">
                        <Button left-icon="bookmark" :icon-fill="0" variant="outline">Outlined Bookmark</Button>
                        <Button left-icon="bookmark" :icon-fill="1" variant="primary">Filled Bookmark</Button>
                        <Button left-icon="heart" :icon-weight="300" variant="ghost">Light Weight</Button>
                        <Button left-icon="heart" :icon-weight="700" variant="danger">Bold Weight</Button>
                    </div>
                </div>

                <!-- Legacy Icons (Backward Compatible) -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Legacy Icons (Auto-mapped)</h3>
                    <div class="flex flex-wrap gap-4">
                        <Button left-icon="plus" variant="success">Add (Legacy)</Button>
                        <Button left-icon="trash" variant="danger">Delete (Legacy)</Button>
                        <Button left-icon="sync" variant="outline">Sync (Legacy)</Button>
                        <Button left-icon="external_link" variant="ghost">External (Legacy)</Button>
                    </div>
                </div>

                <!-- Icon Only Buttons -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Icon-Only Buttons</h3>
                    <div class="flex flex-wrap gap-4">
                        <Button icon-only left-icon="search" variant="outline" />
                        <Button icon-only left-icon="favorite" variant="success" />
                        <Button icon-only left-icon="settings" variant="neutral" />
                        <Button icon-only left-icon="more_vert" variant="ghost" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Standalone Icon Component -->
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Standalone Icon Component</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Basic Usage</h3>
                    <div class="flex items-center gap-4 mb-4">
                        <Icon name="book" size="md" />
                        <Icon name="library_books" size="lg" variant="rounded" />
                        <Icon name="favorite" size="xl" :fill="1" class="text-red-500" />
                        <Icon name="star" size="2xl" variant="sharp" :weight="700" class="text-yellow-500" />
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Different Sizes</h3>
                    <div class="flex items-center gap-4">
                        <Icon name="home" size="xs" />
                        <Icon name="home" size="sm" />
                        <Icon name="home" size="md" />
                        <Icon name="home" size="lg" />
                        <Icon name="home" size="xl" />
                        <Icon name="home" size="2xl" />
                        <Icon name="home" :size="48" />
                    </div>
                </div>
            </div>
        </section>

        <!-- States -->
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">States</h2>
            <div class="space-y-4">
                <div class="flex flex-wrap gap-4">
                    <Button>Normal</Button>
                    <Button disabled>Disabled</Button>
                    <Button :loading="loading" @click="simulateLoading">
                        {{ loading ? 'Loading...' : 'Click to Load' }}
                    </Button>
                </div>

                <div class="flex flex-wrap gap-4">
                    <Button variant="danger" disabled>Disabled Danger</Button>
                    <Button variant="outline" disabled>Disabled Outline</Button>
                    <Button variant="success" :loading="loading">Success Loading</Button>
                </div>
            </div>
        </section>

        <!-- Layout Options -->
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Layout Options</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Full Width</h3>
                    <Button full-width variant="primary">Full Width Button</Button>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Rounded Variants</h3>
                    <div class="flex flex-wrap gap-4">
                        <Button rounded="none">Square</Button>
                        <Button rounded="sm">Small Rounded</Button>
                        <Button rounded="md">Medium Rounded</Button>
                        <Button rounded="lg">Large Rounded</Button>
                        <Button rounded="full">Pill</Button>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Shadow Options</h3>
                    <div class="flex flex-wrap gap-4">
                        <Button>With Shadow (default)</Button>
                        <Button :shadow="false">Without Shadow</Button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Links -->
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Links</h2>
            <div class="flex flex-wrap gap-4">
                <Button href="/search" variant="primary">Internal Link</Button>
                <Button href="https://books.google.com" external variant="outline" right-icon="external_link">External Link</Button>
                <Button to="/library" variant="secondary">Router Link</Button>
            </div>
        </section>

        <!-- Common Use Cases -->
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Common Use Cases</h2>
            <div class="space-y-6">
                <!-- Search Form -->
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Search Form</h3>
                    <div class="flex gap-2">
                        <input
                            type="text"
                            placeholder="Search books..."
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                        />
                        <Button type="submit" left-icon="search">Search</Button>
                        <Button type="button" variant="ghost" left-icon="x">Clear</Button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Action Buttons</h3>
                    <div class="flex flex-wrap gap-2">
                        <Button size="sm" variant="success" left-icon="plus">Add Book</Button>
                        <Button size="sm" variant="outline" left-icon="edit">Edit</Button>
                        <Button size="sm" variant="danger" left-icon="trash">Delete</Button>
                        <Button size="sm" variant="secondary" left-icon="sync">Sync</Button>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Modal Actions</h3>
                    <div class="flex justify-end gap-2">
                        <Button variant="ghost">Cancel</Button>
                        <Button variant="primary">Save Changes</Button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Code Examples -->
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Code Examples</h2>
                <Button size="sm" variant="outline" @click="showExamples = !showExamples">
                    {{ showExamples ? 'Hide' : 'Show' }} Examples
                </Button>
            </div>

            <div v-if="showExamples" class="space-y-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Basic Usage</h3>
                    <pre class="text-sm text-gray-600 overflow-x-auto"><code>&lt;Button&gt;Click me&lt;/Button&gt;
&lt;Button variant="primary"&gt;Primary Button&lt;/Button&gt;
&lt;Button variant="danger" :disabled="true"&gt;Disabled&lt;/Button&gt;</code></pre>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">With Icons</h3>
                    <pre class="text-sm text-gray-600 overflow-x-auto"><code>&lt;Button left-icon="search"&gt;Search&lt;/Button&gt;
&lt;Button right-icon="arrow_right"&gt;Next&lt;/Button&gt;
&lt;Button icon-only left-icon="plus" variant="success" /&gt;</code></pre>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Links</h3>
                    <pre class="text-sm text-gray-600 overflow-x-auto"><code>&lt;Button href="/search"&gt;Internal Link&lt;/Button&gt;
&lt;Button to="/library"&gt;Router Link&lt;/Button&gt;
&lt;Button href="https://example.com" external&gt;External Link&lt;/Button&gt;</code></pre>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Advanced</h3>
                    <pre class="text-sm text-gray-600 overflow-x-auto"><code>&lt;Button
    variant="primary"
    size="lg"
    :loading="isLoading"
    full-width
    left-icon="sync"
    @click="handleSync"
&gt;
    Sync All Books
&lt;/Button&gt;</code></pre>
                </div>
            </div>
        </section>
    </div>
</template>
