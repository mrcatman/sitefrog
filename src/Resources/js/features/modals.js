const selectors = {
    triggers: '[data-sf-modal-trigger]',
    container: '[data-modals-container]',
    modal: '[data-modal]',
    top: '[data-modal-top]',
    template: '[data-modal-template]',
    closeButtons: '[data-modal-close]'
}

const resizeModal = (modal, content) => {
    console.log(content);
    const width = content.clientWidth;
    const height = content.clientHeight;

    modal.style.width = width + 'px';
    modal.style.height = height + 'px';

    modal.style.left = ((window.innerWidth - width) / 2) + 'px';
    modal.style.top = ((window.innerHeight - height) / 2) + 'px';
}

const processModalCloseButtons = (el) => {
    const closeButtons = el.querySelectorAll(selectors.closeButtons);

    Array.from(closeButtons).forEach(closeButton => {
        closeButton.addEventListener('click', e => {
            e.preventDefault();

            const modal = closeButton.closest(selectors.modal);
            modal.parentNode.removeChild(modal);
        })
    });
}

const processModalDrag = (el) => {
    const top = el.querySelectorAll(selectors.top);

    Array.from(top).forEach(topBlock => {
        topBlock.addEventListener('mousedown', e => {
            const modal = topBlock.closest(selectors.modal);
            const rect = modal.getBoundingClientRect();

            topBlock.style.userSelect = 'none';
            topBlock.style.cursor = 'move';
            modal.style.opacity = '.5';

            const startX = e.clientX;
            const startY = e.clientY;
            const onMouseMove = (e) => {
                modal.style.left = (rect.left + (e.clientX - startX)) + 'px';
                modal.style.top = (rect.top + (e.clientY - startY)) + 'px';
            }

            window.addEventListener('mousemove', onMouseMove);
            topBlock.addEventListener('mouseup', () => {
                modal.style.opacity = '';
                topBlock.style.userSelect = '';
                topBlock.style.cursor = '';

                window.removeEventListener('mousemove', onMouseMove)
            })
        })
    });
}

const processModalContent = (el) => {
    processModalCloseButtons(el);
    processModalDrag(el);
}

const processModals = (el) => {
    console.log('process', el);
    const modalTriggers = el.querySelectorAll(selectors.triggers);

    Array.from(modalTriggers).forEach(trigger => {
        trigger.setAttribute('hx-disable', 'true');
        trigger.addEventListener('click', e => {
            e.preventDefault();

            const modalsContainer = document.querySelector(selectors.container);
            const modalTemplate = document.querySelector(selectors.template);

            const modal = modalTemplate.children[0].cloneNode(true)
            modalsContainer.append(modal);

            const randomId = Math.floor(Math.random() * 10000000);
            modal.dataset.randomId = randomId.toString();

            const content = modal.querySelector('[data-modal-content]');
            content.setAttribute('hx-get', trigger.href);
            content.setAttribute('hx-vals', JSON.stringify({
                _sf_modal: true,
                _sf_modal_id: randomId,
            }));

            resizeModal(modal, content);

            htmx.process(modal)
            content.addEventListener('htmx:afterSettle', (e) => {
                processModalContent(modal);
                resizeModal(modal, content)
            });
        })
    })

}

document.addEventListener('DOMContentLoaded', () => processModals(document.body))

document.body.addEventListener('htmx:afterSettle', (e) => processModals(e.target));
