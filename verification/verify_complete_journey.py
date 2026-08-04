import asyncio
import os
from playwright.async_api import async_playwright

async def verify_complete_journey():
    print("Starting Complete User Journey Verification...")
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # Record video of the journey
        os.makedirs("verification/videos", exist_ok=True)
        context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page = await context.new_page()

        # Navigate to homepage
        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_timeout(1000)

        # 1. Trigger Dark mode and Light mode sequence
        theme_button = page.locator("#theme-button")
        await theme_button.click()
        await page.wait_for_timeout(1000)
        await theme_button.click()
        await page.wait_for_timeout(1000)

        # 2. Slow incremental scrolling to trigger ScrollReveal entrance animations nicely
        print("Slow scrolling down to trigger section animations...")
        for y in range(0, 4200, 300):
            await page.mouse.wheel(0, 300)
            await page.wait_for_timeout(150)

        # 3. Fill and submit contact form in simulation mode
        print("Interacting with the contact form...")
        # Scroll contact section into view
        contact_section = page.locator("#contact")
        await contact_section.scroll_into_view_if_needed()
        await page.wait_for_timeout(500)

        # Fill inputs
        await page.locator("#user-name").fill("Budi Santoso")
        await page.locator("#user-email").fill("budi.santoso@gmail.com")
        await page.locator("#user-project").fill("Saya ingin membuat website e-commerce modern untuk UMKM saya.")
        await page.wait_for_timeout(1000)

        # Click send button
        submit_btn = page.locator(".contact__button")
        await submit_btn.click()
        print("Contact form submitted.")

        # Wait for the success response message container to become visible and updated
        message_element = page.locator("#contact-message")
        await message_element.wait_for(state="visible")

        # Wait extra time for fetch request completion
        await page.wait_for_timeout(2000)

        response_text = await message_element.inner_text()
        print(f"Contact form response message: {response_text}")

        # Verify success keywords
        assert "berhasil" in response_text.lower(), "Response message did not indicate success!"
        print("Form submission and success feedback verified successfully!")

        # Capture final journey state screenshot
        os.makedirs("verification/screenshots", exist_ok=True)
        await page.screenshot(path="verification/screenshots/journey_completed_success.png")
        print("Screenshot of successful contact submission saved.")

        # Close context and browser to save the video
        await context.close()
        await browser.close()

    print("Complete User Journey Verification Finished Successfully!")

if __name__ == "__main__":
    asyncio.run(verify_complete_journey())
