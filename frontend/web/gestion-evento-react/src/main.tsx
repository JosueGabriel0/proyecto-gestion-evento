import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { BrowserRouter } from "react-router-dom";
import { HelmetProvider } from "react-helmet-async"; // 👈 Importante
import "./index.css";
import "swiper/swiper-bundle.css";
import "flatpickr/dist/flatpickr.css";
import App from "./App.tsx";
import { AppWrapper } from "./presentation/components/common/PageMeta.tsx";
import { ThemeProvider } from "./presentation/context/ThemeContext.tsx";

createRoot(document.getElementById("root")!).render(
  <StrictMode>
    <ThemeProvider>
      <HelmetProvider>
        <BrowserRouter>
            <AppWrapper>
              <App />
            </AppWrapper>
        </BrowserRouter>
      </HelmetProvider>
    </ThemeProvider>
  </StrictMode>
);