 <style>
     @php $appConfig =$appConfig ?? new \App\Models\UiConfiguration(\App\Models\UiConfiguration::defaults());
     @endphp

     :root {
         /* Label */
         --ui-label-font-family: "{{ $appConfig->label_font_family_css }}", Arial, sans-serif;
         --ui-label-font-size: {{ (int) ($appConfig->label_font_size ?? 14) }}px;
         --ui-label-color: {{ $appConfig->label_color_css ?? '#000000' }};
         --ui-label-font-weight: {{ $appConfig->label_font_weight_css ?? '400' }};

         /* Input */
         --ui-input-font-family: "{{ $appConfig->input_font_family_css }}", Arial, sans-serif;
         --ui-input-font-size: {{ (int) ($appConfig->input_font_size ?? 14) }}px;
         --ui-input-color: {{ $appConfig->input_color_css ?? '#666666' }};
         --ui-input-margin: {{ (int) ($appConfig->input_margin ?? 8) }}px;

         /* Modal */
         --ui-modal-frame-width: {{ (int) ($appConfig->modal_frame_width ?? 1048) }}px;
         --ui-modal-frame-height: {{ (int) ($appConfig->modal_frame_height ?? 600) }}px;

         /* Error Msg */
         --ui-error-font-family: var(--ui-label-font-family);
         --ui-error-font-size: var(--ui-label-font-size);
         --ui-error-font-weight: 400;
     }



     .dynamic-theme-form .form-label {
         font-family: var(--ui-label-font-family);
         font-size: var(--ui-label-font-size);
         color: var(--ui-label-color);
         font-weight: var(--ui-label-font-weight);
     }

     .dynamic-theme-form .form-control,
     .dynamic-theme-form .form-select,
     .dynamic-theme-form textarea {
         font-family: var(--ui-input-font-family);
         font-size: var(--ui-input-font-size);
         color: var(--ui-input-color);
     }

     .dynamic-theme-form .field-spacing,
     .dynamic-theme-form .select2-field-wrapper,
     .dynamic-theme-form .input-group {
         margin-bottom: var(--ui-input-margin);
     }

     .dynamic-theme-label {
         font-family: var(--ui-label-font-family);
         font-size: var(--ui-label-font-size);
         color: var(--ui-label-color);
         font-weight: var(--ui-label-font-weight);
     }

     .dynamic-theme-input,
     .dynamic-theme-select,
     .dynamic-theme-textarea {
         font-family: var(--ui-input-font-family);
         font-size: var(--ui-input-font-size);
         color: var(--ui-input-color);
     }

     .dynamic-theme-field-spacing {
         margin-bottom: var(--ui-input-margin);
     }

     .dynamic-theme-readonly[readonly],
     .dynamic-theme-readonly:read-only {
         background-color: #e9ecef;
         color: #6c757d;
         cursor: not-allowed;
         opacity: 1;
     }

     .dynamic-theme-modal .modal-dialog {
         max-width: var(--ui-modal-frame-width);
     }

     .dynamic-theme-modal .modal-body {
         min-height: var(--ui-modal-frame-height);
     }

     /* CHECK THIS LATER -> --ui-error-font-size: calc(var(--ui-label-font-size) * 0.75); */
     .dynamic-theme-form .invalid-feedback,
     .dynamic-theme-form .invalid-feedback.d-block,
     .dynamic-theme-form .server-error {
         font-family: var(--ui-error-font-family);
         font-size: var(--ui-error-font-size);
         font-weight: var(--ui-error-font-weight);
     }
 </style>
