import asyncio
from playwright.async_api import async_playwright

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page()

        # Desktop view
        await page.set_viewport_size({"width": 1280, "height": 800})
        await page.goto("http://localhost:8080")

        # Scroll to bottom to trigger animations
        await page.evaluate("""
            async () => {
                await new Promise((resolve) => {
                    let totalHeight = 0;
                    let distance = 100;
                    let timer = setInterval(() => {
                        let scrollHeight = document.body.scrollHeight;
                        window.scrollBy(0, distance);
                        totalHeight += distance;
                        if(totalHeight >= scrollHeight){
                            clearInterval(timer);
                            resolve();
                        }
                    }, 100);
                });
            }
        """)

        await page.wait_for_timeout(2000)
        await page.screenshot(path="ar_creative_desktop_scrolled.png", full_page=True)
        print("Desktop scrolled screenshot saved.")

        # Mobile view
        await page.set_viewport_size({"width": 375, "height": 667})
        await page.goto("http://localhost:8080")

        # Scroll to bottom to trigger animations
        await page.evaluate("""
            async () => {
                await new Promise((resolve) => {
                    let totalHeight = 0;
                    let distance = 100;
                    let timer = setInterval(() => {
                        let scrollHeight = document.body.scrollHeight;
                        window.scrollBy(0, distance);
                        totalHeight += distance;
                        if(totalHeight >= scrollHeight){
                            clearInterval(timer);
                            resolve();
                        }
                    }, 100);
                });
            }
        """)

        await page.wait_for_timeout(2000)
        await page.screenshot(path="ar_creative_mobile_scrolled.png", full_page=True)
        print("Mobile scrolled screenshot saved.")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(main())
