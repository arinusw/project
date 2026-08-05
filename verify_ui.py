import os
import sys
import time
from playwright.sync_api import sync_playwright, expect

def trigger_scroll_animations(page):
    # Perform incremental scrolling to trigger ScrollReveal animations on all sections
    print("Scrolling page incrementally to trigger ScrollReveal animations...")
    for y in range(0, 4000, 400):
        page.mouse.wheel(0, 400)
        time.sleep(0.15)
    # Scroll back to top
    page.mouse.wheel(0, -4000)
    time.sleep(0.5)

def verify_journey_desktop(page, base_url):
    print("Navigating to desktop homepage...")
    page.set_viewport_size({"width": 1280, "height": 800})
    page.goto(f"{base_url}/index.html?simulation_mode=1")
    time.sleep(1)

    # Trigger ScrollReveal animations
    trigger_scroll_animations(page)

    # 1. Take home page screenshot with all sections revealed
    page.screenshot(path="verification/screenshots/desktop_home.png", full_page=True)

    # 2. Test theme switcher (Light to Dark)
    theme_button = page.locator("#theme-button")
    expect(theme_button).to_be_visible()
    theme_button.click()
    time.sleep(1)

    # Check if dark theme applied
    expect(page.locator("body")).to_have_class("dark-theme")
    page.screenshot(path="verification/screenshots/desktop_dark_theme.png", full_page=True)

    # Revert to Light Theme
    theme_button.click()
    time.sleep(1)
    expect(page.locator("body")).not_to_have_class("dark-theme")

    # 3. Simulate Contact Form submission (simulation_mode triggered via URL parameter)
    name_input = page.locator("#user_name")
    email_input = page.locator("#user_email")
    project_input = page.locator("#user_project")

    expect(name_input).to_be_visible()
    name_input.fill("Ahmad Ari")
    email_input.fill("ahmad@example.com")
    project_input.fill("Saya tertarik untuk memesan pembuatan website portofolio interaktif.")

    page.locator("#contact-form button[type='submit']").click()

    # Wait for response
    message_element = page.locator("#contact-message")
    message_element.wait_for(state="visible")
    expect(message_element).to_have_class("contact__message success")
    expect(message_element).to_contain_text("Mode Simulasi")

    page.screenshot(path="verification/screenshots/desktop_contact_submitted.png")
    print("Desktop verification completed successfully!")

def verify_journey_mobile(page, base_url):
    print("Navigating to mobile homepage...")
    # Mobile viewport sizes (375x667)
    page.set_viewport_size({"width": 375, "height": 667})
    page.goto(f"{base_url}/index.html?simulation_mode=1")
    time.sleep(1)

    # Trigger ScrollReveal animations for mobile too
    trigger_scroll_animations(page)

    page.screenshot(path="verification/screenshots/mobile_home.png")

    # Verify mobile nav toggle click (force=True used for transitions)
    nav_toggle = page.locator("#nav-toggle")
    expect(nav_toggle).to_be_visible()
    nav_toggle.click(force=True)
    time.sleep(1)

    # Check if show-menu class added to nav-menu
    expect(page.locator("#nav-menu")).to_have_class("nav__menu show-menu")
    page.screenshot(path="verification/screenshots/mobile_menu_open.png")

    # Close mobile menu
    nav_close = page.locator("#nav-close")
    expect(nav_close).to_be_visible()
    nav_close.click()
    time.sleep(1)
    expect(page.locator("#nav-menu")).not_to_have_class("show-menu")

    print("Mobile verification completed successfully!")

if __name__ == "__main__":
    base_url = "http://127.0.0.1:8000"

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()

        try:
            # Create screenshots directory if it doesn't exist
            os.makedirs("verification/screenshots", exist_ok=True)

            # Execute Desktop Journey
            verify_journey_desktop(page, base_url)

            # Execute Mobile Journey
            verify_journey_mobile(page, base_url)

        except Exception as e:
            print(f"Error during journey: {e}")
            sys.exit(1)
        finally:
            browser.close()