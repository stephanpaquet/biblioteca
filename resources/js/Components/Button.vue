<script setup>
import { computed } from 'vue';

const props = defineProps({
    // Button content and functionality
    type: {
        type: String,
        default: 'button',
        validator: (value) => ['button', 'submit', 'reset'].includes(value)
    },
    disabled: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    },

    // Visual variants
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'success', 'danger', 'outline', 'ghost', 'link', 'neutral'].includes(value)
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(value)
    },

    // Layout and styling
    fullWidth: {
        type: Boolean,
        default: false
    },
    rounded: {
        type: String,
        default: 'md',
        validator: (value) => ['none', 'sm', 'md', 'lg', 'full'].includes(value)
    },
    shadow: {
        type: Boolean,
        default: true
    },

    // Icon support
    leftIcon: {
        type: String,
        default: null
    },
    rightIcon: {
        type: String,
        default: null
    },
    iconOnly: {
        type: Boolean,
        default: false
    },

    // Link behavior (when used as router-link or href)
    href: {
        type: String,
        default: null
    },
    to: {
        type: [String, Object],
        default: null
    },
    external: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['click']);

// Computed classes
const buttonClasses = computed(() => {
    const classes = [];

    // Base classes
    classes.push(
        'inline-flex',
        'items-center',
        'justify-center',
        'font-medium',
        'transition-all',
        'duration-200',
        'focus:outline-none',
        'focus:ring-2',
        'focus:ring-offset-2',
        'disabled:cursor-not-allowed'
    );

    // Size classes
    const sizeClasses = {
        xs: 'px-2 py-1 text-xs',
        sm: 'px-3 py-1.5 text-sm',
        md: 'px-4 py-2 text-sm',
        lg: 'px-6 py-3 text-base',
        xl: 'px-8 py-4 text-lg'
    };

    if (props.iconOnly) {
        const iconSizeClasses = {
            xs: 'p-1',
            sm: 'p-1.5',
            md: 'p-2',
            lg: 'p-3',
            xl: 'p-4'
        };
        classes.push(iconSizeClasses[props.size]);
    } else {
        classes.push(sizeClasses[props.size]);
    }

    // Rounded classes
    const roundedClasses = {
        none: 'rounded-none',
        sm: 'rounded-sm',
        md: 'rounded-md',
        lg: 'rounded-lg',
        full: 'rounded-full'
    };
    classes.push(roundedClasses[props.rounded]);

    // Shadow classes
    if (props.shadow && !props.disabled && !props.loading) {
        classes.push('shadow-sm', 'hover:shadow-md');
    }

    // Variant classes
    const variantClasses = {
        primary: {
            base: 'bg-primary-600 text-primary-500 border border-transparent',
            hover: 'hover:bg-primary-700',
            focus: 'focus:ring-primary-500',
            disabled: 'disabled:bg-primary-400 disabled:text-primary-300'
        },
        secondary: {
            base: 'bg-secondary-600 text-primary-500 border border-transparent',
            hover: 'hover:bg-secondary-700',
            focus: 'focus:ring-secondary-500',
            disabled: 'disabled:bg-secondary-400 disabled:text-primary-500'
        },
        success: {
            base: 'bg-success-600 text-primary-500 border border-transparent',
            hover: 'hover:bg-success-700',
            focus: 'focus:ring-success-500',
            disabled: 'disabled:bg-success-400 disabled:text-primary-500'
        },
        danger: {
            base: 'bg-danger-600 text-primary-500 border border-transparent',
            hover: 'hover:bg-danger-700',
            focus: 'focus:ring-danger-500',
            disabled: 'disabled:bg-danger-400 disabled:text-primary-500'
        },
        outline: {
            base: 'bg-transparent text-primary-600 border border-primary-300',
            hover: 'hover:bg-primary-50 hover:text-primary-700 hover:border-primary-400',
            focus: 'focus:ring-primary-500',
            disabled: 'disabled:bg-gray-50 disabled:text-gray-400 disabled:border-gray-300'
        },
        ghost: {
            base: 'bg-gray-100 text-gray-700 border border-gray-200',
            hover: 'hover:bg-gray-200 hover:text-gray-900 hover:border-gray-300',
            focus: 'focus:ring-gray-500',
            disabled: 'disabled:bg-gray-50 disabled:text-gray-400 disabled:border-gray-200'
        },
        neutral: {
            base: 'bg-white text-gray-700 border border-gray-300',
            hover: 'hover:bg-gray-50 hover:text-gray-900 hover:border-gray-400',
            focus: 'focus:ring-gray-500',
            disabled: 'disabled:bg-gray-50 disabled:text-gray-400 disabled:border-gray-300'
        },
        link: {
            base: 'bg-transparent text-primary-600 border border-transparent underline-offset-4',
            hover: 'hover:underline hover:text-primary-700',
            focus: 'focus:ring-primary-500',
            disabled: 'disabled:text-gray-400 disabled:no-underline'
        }
    };

    const variant = variantClasses[props.variant];
    classes.push(variant.base);

    if (!props.disabled && !props.loading) {
        classes.push(variant.hover);
    }

    classes.push(variant.focus);
    classes.push(variant.disabled);

    // Full width
    if (props.fullWidth) {
        classes.push('w-full');
    }

    // Loading state
    if (props.loading) {
        classes.push('cursor-wait');
    }

    return classes.join(' ');
});

// Determine component type
const componentType = computed(() => {
    if (props.href) return 'a';
    if (props.to) return 'router-link';
    return 'button';
});

// Component props for dynamic component
const componentProps = computed(() => {
    const baseProps = {
        class: buttonClasses.value,
        disabled: props.disabled || props.loading
    };

    if (props.href) {
        baseProps.href = props.href;
        if (props.external) {
            baseProps.target = '_blank';
            baseProps.rel = 'noopener noreferrer';
        }
    } else if (props.to) {
        baseProps.to = props.to;
    } else {
        baseProps.type = props.type;
    }

    return baseProps;
});

// Click handler
const handleClick = (event) => {
    if (props.disabled || props.loading) {
        event.preventDefault();
        return;
    }
    emit('click', event);
};

// Loading icon SVG
const loadingIcon = `
<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
`;

// Icon helper function
const getIconSvg = (iconName) => {
    const icons = {
        // Common icons
        search: `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>`,
        plus: `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>`,
        minus: `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path></svg>`,
        check: `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`,
        x: `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`,
        arrow_left: `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>`,
        arrow_right: `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>`,
        sync: `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>`,
        trash: `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>`,
        edit: `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>`,
        external_link: `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>`
    };

    return icons[iconName] || '';
};
</script>

<template>
    <component
        :is="componentType"
        v-bind="componentProps"
        @click="handleClick"
    >
        <!-- Loading state -->
        <template v-if="loading">
            <span v-html="loadingIcon" class="mr-2"></span>
            <span v-if="!iconOnly">Loading...</span>
        </template>

        <!-- Normal state -->
        <template v-else>
            <!-- Left icon -->
            <span
                v-if="leftIcon && !iconOnly"
                v-html="getIconSvg(leftIcon)"
                class="mr-2"
            ></span>

            <!-- Icon only -->
            <span
                v-if="iconOnly && (leftIcon || rightIcon)"
                v-html="getIconSvg(leftIcon || rightIcon)"
            ></span>

            <!-- Button content -->
            <span v-if="!iconOnly">
                <slot></slot>
            </span>

            <!-- Right icon -->
            <span
                v-if="rightIcon && !iconOnly"
                v-html="getIconSvg(rightIcon)"
                class="ml-2"
            ></span>
        </template>
    </component>
</template>
