<template>
  <Layout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
              <nav class="flex space-x-4">
                <Link
                  :href="route('admin.index')"
                  :class="[
                    'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                    route().current('admin.index')
                      ? 'bg-blue-100 text-blue-700'
                      : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                  ]"
                >
                  Users & Roles
                </Link>
                <Link
                  :href="route('admin.libraries')"
                  :class="[
                    'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                    route().current('admin.libraries')
                      ? 'bg-blue-100 text-blue-700'
                      : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                  ]"
                >
                  Libraries
                </Link>
              </nav>
            </div>

            <!-- Users & Roles Management -->
            <div class="mb-8">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Users & Roles</h2>

              <!-- Users Table -->
              <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                  <li class="px-6 py-4 bg-gray-50">
                    <div class="flex items-center justify-between">
                      <div class="flex-1 grid grid-cols-4 gap-4">
                        <div class="font-medium text-gray-900">Name</div>
                        <div class="font-medium text-gray-900">Email</div>
                        <div class="font-medium text-gray-900">Current Role</div>
                        <div class="font-medium text-gray-900">Actions</div>
                      </div>
                    </div>
                  </li>
                  <li v-for="user in users.data" :key="user.id" class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                      <div class="flex-1 grid grid-cols-4 gap-4">
                        <div class="text-sm text-gray-900">{{ user.name }}</div>
                        <div class="text-sm text-gray-500">{{ user.email }}</div>
                        <div class="text-sm">
                          <span
                            v-for="role in user.roles"
                            :key="role.id"
                            :class="[
                              'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-2',
                              getRoleColor(role.name),
                            ]"
                          >
                            {{ role.name }}
                          </span>
                        </div>
                        <div class="text-sm">
                          <select
                            :value="user.roles[0]?.name || ''"
                            class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            @change="assignRole(user, $event.target.value)"
                          >
                            <option value="">Select Role</option>
                            <option v-for="role in roles" :key="role.id" :value="role.name">
                              {{ role.name }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>

              <!-- Pagination -->
              <div class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-700">
                  Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results
                </div>
                <div class="flex space-x-2">
                  <Link
                    v-if="users.prev_page_url"
                    :href="users.prev_page_url"
                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                  >
                    Previous
                  </Link>
                  <Link
                    v-if="users.next_page_url"
                    :href="users.next_page_url"
                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                  >
                    Next
                  </Link>
                </div>
              </div>
            </div>

            <!-- Roles & Permissions Overview -->
            <div class="mb-8">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">Roles & Permissions</h2>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                  v-for="role in roles"
                  :key="role.id"
                  class="bg-white border border-gray-200 rounded-lg p-4"
                >
                  <h3 class="text-lg font-medium text-gray-900 mb-2">{{ role.name }}</h3>
                  <p class="text-sm text-gray-600 mb-3">{{ getRoleDescription(role.name) }}</p>
                  <div class="space-y-1">
                    <div
                      v-for="permission in role.permissions"
                      :key="permission.id"
                      class="text-xs text-gray-500"
                    >
                      • {{ permission.name }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Layout from '@/Layouts/Layout.vue';

defineProps({
  users: Object,
  roles: Array,
  permissions: Array,
});

const getRoleColor = (roleName) => {
  const colors = {
    admin: 'bg-red-100 text-red-800',
    librarian: 'bg-yellow-100 text-yellow-800',
    user: 'bg-green-100 text-green-800',
  };
  return colors[roleName] || 'bg-gray-100 text-gray-800';
};

const getRoleDescription = (roleName) => {
  const descriptions = {
    admin: 'Full system access including user management and system settings.',
    librarian: 'Can manage books and view all libraries but cannot manage users.',
    user: 'Basic access to manage their own library and books.',
  };
  return descriptions[roleName] || 'Custom role with specific permissions.';
};

const assignRole = async (user, roleName) => {
  if (!roleName) return;

  try {
    await router.post(route('admin.assign-role', user.id), {
      role: roleName,
    });

    // Refresh the page to show updated roles
    router.reload();
  } catch (error) {
    console.error('Error assigning role:', error);
    alert('Error assigning role. Please try again.');
  }
};
</script>
