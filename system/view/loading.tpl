<!-- LOADING -->
{literal}
  <style>
    html, body {
      max-width: 100vw;
      overflow-x: auto;
    }
    .page-loader {
      width: 100%;
      height: 100%;
      position: fixed;
      top: 0;
      left: 0;
      background-color: rgba(255, 255, 255, 1);
      z-index: 1500;
      align-items: center;
      justify-content: center;
      display: flex;
    }

    .page-loader__spinner {
      position: relative;
      width: 50px;
      height: 50px;
    }

    .page-loader__spinner svg {
      animation: rotate 2s linear infinite;
      transform-origin: center center;
      width: 100%;
      height: 100%;
      position: absolute;
      top: 0;
      left: 0;
    }

    .page-loader__spinner svg circle {
      stroke-dasharray: 1, 200;
      stroke-dashoffset: 0;
      animation: dash 1.5s ease-in-out infinite, color 9s ease-in-out infinite;
      stroke-linecap: round;
    }

    @keyframes rotate {
      100% {
        transform: rotate(360deg)
      }
    }

    @keyframes dash {
      0% {
        stroke-dasharray: 1, 200;
        stroke-dashoffset: 0
      }
      50% {
        stroke-dasharray: 89, 200;
        stroke-dashoffset: -35px
      }
      100% {
        stroke-dasharray: 89, 200;
        stroke-dashoffset: -124px
      }
    }

    @keyframes color {
      0%, 100% {
        stroke: #3078bf
      }
      50% {
        stroke: #455a64
      }
    }
  </style>
{/literal}
<div class="page-loader">
  <div class="page-loader__spinner">
    <svg viewBox="25 25 50 50">
      <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
    </svg>
  </div>
</div>
<!-- ./LOADING -->