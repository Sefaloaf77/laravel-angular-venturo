import { Component, OnInit, AfterViewInit, Inject } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { DOCUMENT } from '@angular/common';

import { AuthService } from 'src/app/feature/auth/services/auth.service';
import { EventService } from '../../core/services/event.service';
@Component({
    selector: 'app-horizontaltopbar',
    templateUrl: './horizontaltopbar.component.html',
})

/**
 * Horizontal Topbar and navbar specified
 */
export class HorizontaltopbarComponent implements OnInit, AfterViewInit {

    element;
    configData;
    userLogin;

    constructor(
        @Inject(DOCUMENT) private document: any,
        private router: Router,
        private eventService: EventService,
        private authService: AuthService
    ) {
        router.events.subscribe(event => {
            if (event instanceof NavigationEnd) {
                this.activateMenu();
            }
        });
    }

    ngOnInit(): void {
        this.element = document.documentElement;

        this.configData = {
            suppressScrollX: true,
            wheelSpeed: 0.3
        };

        this.authService.getProfile().subscribe((user: any) => {
            this.userLogin = user;
        });
    }

    logout() {
        this.authService.logout();
        this.router.navigate(['auth/login']);
    }

    /**
     * On menu click
     */
    onMenuClick(event: any) {
        const nextEl = event.target.nextSibling;
        if (nextEl && !nextEl.classList.contains('show')) {
            const parentEl = event.target.parentNode;
            if (parentEl) { parentEl.classList.remove('show'); }

            nextEl.classList.add('show');
        } else if (nextEl) { nextEl.classList.remove('show'); }
        return false;
    }

    ngAfterViewInit() {
        this.activateMenu();
    }

    /**
     * remove active and mm-active class
     */
    _removeAllClass(className) {
        const els = document.getElementsByClassName(className);
        while (els[0]) {
            els[0].classList.remove(className);
        }
    }

    /**
     * Togglemenu bar
     */
    toggleMenubar() {
        const element = document.getElementById('topnav-menu-content');
        element.classList.toggle('show');
    }

    /**
     * Activates the menu
     */
    private activateMenu() {

        const resetParent = (el: any) => {
            const parent = el.parentElement;
            if (parent) {
                parent.classList.remove('active');
                const parent2 = parent.parentElement;
                this._removeAllClass('mm-active');
                this._removeAllClass('mm-show');
                if (parent2) {
                    parent2.classList.remove('active');
                    const parent3 = parent2.parentElement;
                    if (parent3) {
                        parent3.classList.remove('active');
                        const parent4 = parent3.parentElement;
                        if (parent4) {
                            parent4.classList.remove('active');
                            const parent5 = parent4.parentElement;
                            if (parent5) {
                                parent5.classList.remove('active');
                            }
                        }
                    }
                }
            }
        };

        // activate menu item based on location
        const links = document.getElementsByClassName('side-nav-link-ref');
        let matchingMenuItem = null;

        for (const key in links) {
            // reset menu
            resetParent(links[key]);
        }

        for (const key in links) {
            if (location.pathname === links[key]['pathname']) {
                matchingMenuItem = links[key];
                break;
            }
        }

        if (matchingMenuItem) {
            const parent = matchingMenuItem.parentElement;
            if (parent) {
                parent.classList.add('active');
                const parent2 = parent.parentElement;
                if (parent2) {
                    parent2.classList.add('active');
                    const parent3 = parent2.parentElement;
                    if (parent3) {
                        parent3.classList.add('active');
                        const parent4 = parent3.parentElement;
                        if (parent4) {
                            parent4.classList.add('active');
                            const parent5 = parent4.parentElement;
                            if (parent5) {
                                parent5.classList.add('active');
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * on settings button clicked from topbar
     */
    onSettingsButtonClicked() {
        document.body.classList.toggle('right-bar-enabled');
    }

    /**
     * Fullscreen method
     */
    fullscreen() {
        document.body.classList.toggle('fullscreen-enable');
        if (!document.fullscreenElement && !this.element.mozFullScreenElement && !this.element.webkitFullscreenElement) {
            if (this.element.requestFullscreen) {
                this.element.requestFullscreen();
            } else if (this.element.mozRequestFullScreen) {
                /* Firefox */
                this.element.mozRequestFullScreen();
            } else if (this.element.webkitRequestFullscreen) {
                /* Chrome, Safari and Opera */
                this.element.webkitRequestFullscreen();
            } else if (this.element.msRequestFullscreen) {
                /* IE/Edge */
                this.element.msRequestFullscreen();
            }
        } else {
            if (this.document.exitFullscreen) {
                this.document.exitFullscreen();
            } else if (this.document.mozCancelFullScreen) {
                /* Firefox */
                this.document.mozCancelFullScreen();
            } else if (this.document.webkitExitFullscreen) {
                /* Chrome, Safari and Opera */
                this.document.webkitExitFullscreen();
            } else if (this.document.msExitFullscreen) {
                /* IE/Edge */
                this.document.msExitFullscreen();
            }
        }
    }

    /**
     * Change the layout onclick
     * @param layout Change the layout
     */
    changeLayout(layout: string) {
        this.eventService.broadcast('changeLayout', layout);
        window.location.reload();
    }
}
