/**
 * Class to initialize tabs based on simple wrappers
 *
 * Options:
 *  - selector:             string                  Selector of the tab group
 *  - content:              string                  Selector of the tab content wrapper
 *  - cssClasses:           object                  Defines the CSS classes which will be set automatically.
 *    - active:             string                  CSS class for the active state.
 *
 * @author Sebastian Zoglowek <https://github.com/zoglo>
 */

export default class Tabs
{
    tabs = []
    options = {
        selector: '.tab-nav',
        content:  '.tab-content>.inside',
        cssClasses: {
            active:  'active'
        },
    }

    constructor(options = {})
    {
        this.options = Object.assign({}, this.options, options)

        this.navigations = document.querySelectorAll(this.options.selector)

        if (!this.navigations)
            return

        this.tabs = []
        this.navs = []

        this._initTabs()
    }

    /**
     * @private
     */
    _checkDeepLink()
    {
        const hash = window.location.hash

        if ('' === hash || !hash.startsWith("#tab=")) return

        const index = hash.slice(5)

        Object.entries(this.navs).every(([k, v]) => {
            if (index !== v.dataset.tabNav)
                return true

            v.click()
            v.focus()
            return false
        })
    }

    /**
     * @private
     */
    _bindEvents()
    {
        const active = this.options.cssClasses.active

        this.navs.forEach(nav => {
            nav.onclick = e => {
                const tabId = e.target.dataset.tabToggle

                this.navs.forEach(i => {
                    i.classList.remove(active)
                    i.ariaExpanded = false
                })

                Object.entries(this.tabs).forEach(([k, v]) => {
                    v.classList.remove(active)
                    if (tabId === v.dataset.tabId)
                        v.classList.add(active)
                })

                e.target.classList.add(active)
                e.target.ariaExpanded = true
            }
        })

        this._checkDeepLink()

        onhashchange = () => this._checkDeepLink()
    }

    /**
     * @param navContainer
     * @private
     */
    _initTabGroup(navContainer)
    {
        this.navs = navContainer.querySelectorAll('[data-tab-toggle]')
        this.tabs = navContainer.nextElementSibling.firstElementChild.children
        this.tabs[0]?.classList.add(this.options.cssClasses.active)

        this._bindEvents()
    }

    /**
     * @private
     */
    _initTabs()
    {
        this.navigations.forEach((navContainer) => {
            this._initTabGroup(navContainer)
        })
    }
}
