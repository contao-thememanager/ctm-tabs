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

        this._initTabs()
    }

    /**
     * Iterate through every found tab group on the page
     *
     * @private
     */
    _initTabs()
    {
        this.navigations.forEach((navContainer) => {
            this._initTabGroup(navContainer)
        });
    }

    /**
     * Initialize a tab group
     *
     * @param list
     * @private
     */
    _initTabGroup(navContainer)
    {
        const navs  = navContainer.querySelectorAll('[data-tab-toggle]')
        const tabs = navContainer.nextElementSibling.firstElementChild.children

        tabs[0].classList.add(this.options.cssClasses.active)

        //this._buildStructure(list, tabs)

        navs.forEach(nav => this._bindEvents(nav, navs, tabs))
    }

    /**
     * Build the wrapper
     *
     * @param list
     * @param tabs
     * @private
     */
    _buildStructure(list, tabs)
    {
        const wrapper = list.querySelector(this.options.content)
        tabs.forEach(tab => wrapper.append(tab))
        tabs[0].classList.add(this.options.cssClasses.active)
    }

    /**
     * Bind the tab events for the buttons and their content
     *
     * @param nav
     * @param navs
     * @param tabs
     * @private
     */
    _bindEvents(nav, navs, tabs)
    {
        const active = this.options.cssClasses.active

        nav.onclick = e => {
            navs.forEach(i => i.classList.remove(active))

            Object.entries(tabs).forEach(([k, v]) => {
                v.classList.remove(active)
                if (e.target.dataset.tabToggle === v.dataset.tabId)
                    v.classList.add(active)
            })

            e.target.classList.add(active)
        }
    }
}
