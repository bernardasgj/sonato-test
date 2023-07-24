/**
 * @class PopupHandler - Handles popups
 * 
 * Basic usage:
 * <i data-popup-trigger="1"></i>
 * <div data-popup-content="1"></div>
 * On click on the the trigger popup content will be shown
 * 
 * 
 * Komentaras: overkill I know daryt general popup klase vienam komponentui
 */
class PopupHandler {
  constructor() {
      this.popupTriggers = document.querySelectorAll('[data-popup-trigger]');
      this.initializePopups();
  }

  initializePopups() {
      this.popupTriggers.forEach((trigger) => {
          const popupId = trigger.getAttribute('data-popup-trigger');
          const popupContent = document.querySelector(`[data-popup-content="${popupId}"]`);

          if (!popupContent) return;

          trigger.addEventListener('click', () => {
              if (popupContent.style.display === 'block') {
                  popupContent.style.display = 'none';
              } else {
                  this.hideAllPopup();
                  popupContent.style.display = 'block';
                  this.firePopupEvent(popupId);
              }
          });

          document.addEventListener('click', (event) => {
              if (!popupContent.contains(event.target) && event.target !== trigger) {
                  popupContent.style.display = 'none';
              }
          });
      });
  }

  hideAllPopup() {
      const allPopupContents = document.querySelectorAll('[data-popup-content]');
      allPopupContents.forEach((content) => {
          content.style.display = 'none';
      });
  }

  firePopupEvent(popupId) {
      const event = new CustomEvent(popupId);
      // Document nera gera vieta fire event, bet nemanau, kad verta back-end teste full parent child structures kaip React'e ar extjs daryt.
      // bad practice, bet for the scope of the test i.e. parodyt, kad classes should be separate and comunicate via events, per daug 
      // neissipleciant - somewhat manau works
      document.dispatchEvent(event);
  }
}

const popupHandler = new PopupHandler();
