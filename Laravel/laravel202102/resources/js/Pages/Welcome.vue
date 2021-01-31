<template>
  <div
    class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0"
  >
    <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
      <inertia-link
        v-if="$page.props.user"
        href="/dashboard"
        class="text-sm text-gray-700 underline"
      >Dashboard</inertia-link>

      <template v-else>
        <inertia-link :href="route('login')" class="text-sm text-gray-700 underline">Login</inertia-link>

        <inertia-link
          v-if="canRegister"
          :href="route('register')"
          class="ml-4 text-sm text-gray-700 underline"
        >Register</inertia-link>
      </template>
    </div>

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
        <svg
          x="0px"
          y="0px"
          width="1852.5265"
          height="1431.5159"
          viewBox="0 0 1852.5265 1431.5159"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
          class="h-16 w-auto text-gray-700 sm:h-20"
        >
          <g clip-path="url(#clip0)" fill="#EF3B2D">
            <polygon
              style="clip-rule:evenodd;fill:#ed1c24;fill-rule:evenodd;stroke-width:0.09486936"
              id="polygon7"
              points="393.252,364.146 361.118,330.593 251.231,330.416 217.504,364.146 302.271,466.223 "
              transform="matrix(10.540811,0,0,10.540811,-2292.6686,-3482.8526)"
            />
            <path
              style="clip-rule:evenodd;fill:#fff200;fill-rule:evenodd;stroke-width:1"
              inkscape:connector-curvature="0"
              id="path9"
              d="m 155.40325,361.91884 236.55688,-244.46251 148.61491,1.8341 c -229.484,110.9526 -285.0868,261.5808 -247.17149,398.82216 z m 523.25643,629.2443 c 183.73689,50.34296 297.1244,58.63856 460.52812,8.4959 L 903.91683,1246.2297 Z M 366.98895,637.87729 558.76849,853.24716 C 587.90328,740.42885 890.88838,809.38685 917.47231,901.77705 1044.468,943.56083 1260.8814,872.46306 1263.9066,806.24568 1263.3163,646.52076 525.76521,779.03983 366.98895,637.87729 Z M 1499.7889,157.00546 1494.645,384.84511 h -298.7477 c 4.0477,-150.63874 -110.4255,-224.61416 -330.81282,-218.54265 -155.18184,3.03576 -304.29216,63.68758 -306.31599,163.77259 0,237.57936 835.54901,-19.20537 902.27241,312.3875 l 242.3122,-280.21694 z m -355.3518,-36.87176 158.4811,0.18973 -34.732,117.95169 c -29.2086,-71.414 -78.3815,-97.48142 -123.7491,-118.14142 z"
            />
          </g>
        </svg>
      </div>

      <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2">
          <div class="p-6">
            <div class="flex items-center">
              <svg
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                class="w-8 h-8 text-gray-500"
              >
                <path
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                />
              </svg>
              <div class="ml-4 text-lg leading-7 font-semibold">
                <a
                  href="https://laravel.com/docs"
                  class="underline text-gray-900 dark:text-white"
                >Platna lista</a>
              </div>
            </div>

            <div class="ml-12">
              <div
                class="mt-2 text-gray-600 dark:text-gray-400 text-sm"
              >App za generiranje platne liste</div>
            </div>
          </div>

          <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
            <div class="flex items-center">
              <svg
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                class="w-8 h-8 text-gray-500"
              >
                <path
                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                />
                <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <div class="ml-4 text-lg leading-7 font-semibold">
                <a
                  href="https://laracasts.com"
                  class="underline text-gray-900 dark:text-white"
                >React Platna lista</a>
              </div>
            </div>

            <div class="ml-12">
              <div
                class="mt-2 text-gray-600 dark:text-gray-400 text-sm"
              >React verzija Platne liste.</div>
            </div>
          </div>

          <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
              <svg
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                class="w-8 h-8 text-gray-500"
              >
                <path
                  d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
                />
              </svg>
              <div class="ml-4 text-lg leading-7 font-semibold">
                <a
                  href="https://laravel-news.com/"
                  class="underline text-gray-900 dark:text-white"
                >Chat</a>
              </div>
            </div>

            <div class="ml-12">
              <div
                class="mt-2 text-gray-600 dark:text-gray-400 text-sm"
              >Chat app.</div>
            </div>
          </div>

          <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-l">
            <div class="flex items-center">
              <svg
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                class="w-8 h-8 text-gray-500"
              >
                <path
                  d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <div
                class="ml-4 text-lg leading-7 font-semibold text-gray-900 dark:text-white"
              >Vibrant Ecosystem</div>
            </div>

            <div class="ml-12">
              <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                Laravel's robust library of first-party tools and libraries, such as
                <a
                  href="https://forge.laravel.com"
                  class="underline"
                >Forge</a>,
                <a href="https://vapor.laravel.com" class="underline">Vapor</a>,
                <a href="https://nova.laravel.com" class="underline">Nova</a>, and
                <a href="https://envoyer.io" class="underline">Envoyer</a> help you take your projects to the next level. Pair them with powerful open source libraries like
                <a
                  href="https://laravel.com/docs/billing"
                  class="underline"
                >Cashier</a>,
                <a href="https://laravel.com/docs/dusk" class="underline">Dusk</a>,
                <a href="https://laravel.com/docs/broadcasting" class="underline">Echo</a>,
                <a href="https://laravel.com/docs/horizon" class="underline">Horizon</a>,
                <a href="https://laravel.com/docs/sanctum" class="underline">Sanctum</a>,
                <a href="https://laravel.com/docs/telescope" class="underline">Telescope</a>, and more.
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
        <div class="text-center text-sm text-gray-500 sm:text-left">
          <div class="flex items-center">
            <svg
              fill="none"
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              viewBox="0 0 24 24"
              class="ml-4 -mt-px w-5 h-5 text-gray-400"
            >
              <path
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
              />
            </svg>

            Sprite &copy; 2021
          </div>
        </div>

        <div
          class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0"
        >Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.bg-gray-100 {
  background-color: #f7fafc;
  background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
}

.border-gray-200 {
  border-color: #edf2f7;
  border-color: rgba(237, 242, 247, var(--tw-border-opacity));
}

.text-gray-400 {
  color: #cbd5e0;
  color: rgba(203, 213, 224, var(--tw-text-opacity));
}

.text-gray-500 {
  color: #a0aec0;
  color: rgba(160, 174, 192, var(--tw-text-opacity));
}

.text-gray-600 {
  color: #718096;
  color: rgba(113, 128, 150, var(--tw-text-opacity));
}

.text-gray-700 {
  color: #4a5568;
  color: rgba(74, 85, 104, var(--tw-text-opacity));
}

.text-gray-900 {
  color: #1a202c;
  color: rgba(26, 32, 44, var(--tw-text-opacity));
}

@media (prefers-color-scheme: dark) {
  .dark\:bg-gray-800 {
    background-color: #2d3748;
    background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
  }

  .dark\:bg-gray-900 {
    background-color: #1a202c;
    background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
  }

  .dark\:border-gray-700 {
    border-color: #4a5568;
    border-color: rgba(74, 85, 104, var(--tw-border-opacity));
  }

  .dark\:text-white {
    color: #fff;
    color: rgba(255, 255, 255, var(--tw-text-opacity));
  }

  .dark\:text-gray-400 {
    color: #cbd5e0;
    color: rgba(203, 213, 224, var(--tw-text-opacity));
  }
}
</style>

<script>
export default {
  props: {
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String
  }
};
</script>
