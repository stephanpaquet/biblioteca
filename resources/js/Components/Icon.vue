<script setup>
import { computed } from 'vue';

const props = defineProps({
  // Icon name (Material Symbols name)
  name: {
    type: String,
    required: true,
  },

  // Icon variant (outlined, rounded, sharp)
  variant: {
    type: String,
    default: 'outlined',
    validator: (value) => ['outlined', 'rounded', 'sharp'].includes(value),
  },

  // Icon size (px or predefined sizes)
  size: {
    type: [String, Number],
    default: 'md',
    validator: (value) => {
      if (typeof value === 'number') return value > 0;
      return ['xs', 'sm', 'md', 'lg', 'xl', '2xl'].includes(value) || /^\d+$/.test(value);
    },
  },

  // Fill (0 = outlined, 1 = filled)
  fill: {
    type: [Number, Boolean],
    default: 0,
    validator: (value) => [0, 1, true, false].includes(value),
  },

  // Weight (100-700)
  weight: {
    type: Number,
    default: 400,
    validator: (value) => value >= 100 && value <= 700,
  },

  // Grade (-25 to 200)
  grade: {
    type: Number,
    default: 0,
    validator: (value) => value >= -50 && value <= 200,
  },

  // Optical size (20-48)
  opticalSize: {
    type: Number,
    default: 24,
    validator: (value) => value >= 20 && value <= 48,
  },

  // Custom CSS classes
  class: {
    type: String,
    default: '',
  },
});

// Size mapping for predefined sizes
const sizeMap = {
  xs: '16px',
  sm: '20px',
  md: '24px',
  lg: '28px',
  xl: '32px',
  '2xl': '40px',
};

// Computed class name for Material Symbols
const iconClass = computed(() => {
  const classes = ['material-symbols'];

  // Add variant class
  switch (props.variant) {
    case 'rounded':
      classes.push('material-symbols-rounded');
      break;
    case 'sharp':
      classes.push('material-symbols-sharp');
      break;
    default:
      classes.push('material-symbols-outlined');
  }

  return classes.join(' ');
});

// Computed styles for the icon
const iconStyle = computed(() => {
  const styles = {};

  // Font size
  if (typeof props.size === 'number') {
    styles.fontSize = `${props.size}px`;
  } else if (sizeMap[props.size]) {
    styles.fontSize = sizeMap[props.size];
  } else if (/^\d+$/.test(props.size)) {
    styles.fontSize = `${props.size}px`;
  }

  // Font variation settings
  const variations = [];
  variations.push(`'FILL' ${props.fill === true ? 1 : props.fill === false ? 0 : props.fill}`);
  variations.push(`'wght' ${props.weight}`);
  variations.push(`'GRAD' ${props.grade}`);
  variations.push(`'opsz' ${props.opticalSize}`);

  styles.fontVariationSettings = variations.join(', ');

  return styles;
});

// Legacy icon mapping (for backward compatibility with existing SVG icons)
const legacyIconMap = {
  // Map old icon names to Material Symbols names
  search: 'search',
  plus: 'add',
  minus: 'remove',
  check: 'check',
  x: 'close',
  arrow_left: 'arrow_back',
  arrow_right: 'arrow_forward',
  sync: 'sync',
  trash: 'delete',
  edit: 'edit',
  external_link: 'open_in_new',
  // Add more mappings as needed
  add: 'add',
  remove: 'remove',
  close: 'close',
  menu: 'menu',
  home: 'home',
  book: 'book',
  library: 'library_books',
  person: 'person',
  settings: 'settings',
  info: 'info',
  warning: 'warning',
  error: 'error',
  success: 'check_circle',
  favorite: 'favorite',
  star: 'star',
  share: 'share',
  download: 'download',
  upload: 'upload',
  refresh: 'refresh',
  visibility: 'visibility',
  visibility_off: 'visibility_off',
  language: 'language',
  dark_mode: 'dark_mode',
  light_mode: 'light_mode',
  filter: 'filter_list',
  sort: 'sort',
  expand_more: 'expand_more',
  expand_less: 'expand_less',
  chevron_left: 'chevron_left',
  menu_book: 'menu_book',
  clear: 'clear',
  search_off: 'search_off',
  bookmark_border: 'bookmark_border',
  auto_stories: 'auto_stories',
  task_alt: 'task_alt',
  expand_more: 'expand_more',
  chevron_right: 'chevron_right',
  first_page: 'first_page',
  last_page: 'last_page',
  navigate_before: 'navigate_before',
  navigate_next: 'navigate_next',
  more_horiz: 'more_horiz',
  more_vert: 'more_vert',
};

// Get the actual icon name (with legacy mapping support)
const iconName = computed(() => {
  return legacyIconMap[props.name] || props.name;
});
</script>

<template>
  <span :class="[iconClass, props.class]" :style="iconStyle" role="img" :aria-label="iconName">
    {{ iconName }}
  </span>
</template>

<style scoped>
.material-symbols {
  font-family: 'Material Symbols Outlined';
  font-style: normal;
  font-display: block;
  user-select: none;
  vertical-align: middle;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.material-symbols-rounded {
  font-family: 'Material Symbols Rounded';
}

.material-symbols-sharp {
  font-family: 'Material Symbols Sharp';
}

/* Default styles for better alignment */
.material-symbols,
.material-symbols-rounded,
.material-symbols-sharp {
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
}
</style>
