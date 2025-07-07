<script setup>
import { computed } from 'vue';
import Icon from './Icon.vue';

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

    // Icon customization (passed to Icon component)
    iconVariant: {
        type: String,
        default: 'outlined'
    },
    iconFill: {
        type: [Number, Boolean],
        default: 0
    },
    iconWeight: {
        type: Number,
        default: 400
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
    }    emit('click', event);
};

// Computed icon size based on button size
const iconSize = computed(() => {
    const sizeMap = {
        xs: 'sm',
        sm: 'sm',
        md: 'md',
        lg: 'lg',
        xl: 'xl'
    };
    return sizeMap[props.size] || 'md';
});
</script>

<template>
    <component
        :is="componentType"
        v-bind="componentProps"
        @click="handleClick"
    >
        <!-- Loading state -->
        <template v-if="loading">
            <Icon
                name="progress_activity"
                :size="iconSize"
                :variant="iconVariant"
                class="animate-spin mr-2"
            />
            <span v-if="!iconOnly">Loading...</span>
        </template>

        <!-- Normal state -->
        <template v-else>
            <!-- Left icon -->
            <Icon
                v-if="leftIcon && !iconOnly"
                :name="leftIcon"
                :size="iconSize"
                :variant="iconVariant"
                :fill="iconFill"
                :weight="iconWeight"
                class="mr-2"
            />

            <!-- Icon only -->
            <Icon
                v-if="iconOnly && (leftIcon || rightIcon)"
                :name="leftIcon || rightIcon"
                :size="iconSize"
                :variant="iconVariant"
                :fill="iconFill"
                :weight="iconWeight"
            />

            <!-- Button content -->
            <span v-if="!iconOnly">
                <slot></slot>
            </span>

            <!-- Right icon -->
            <Icon
                v-if="rightIcon && !iconOnly"
                :name="rightIcon"
                :size="iconSize"
                :variant="iconVariant"
                :fill="iconFill"
                :weight="iconWeight"
                class="ml-2"
            />
        </template>
    </component>
</template>
