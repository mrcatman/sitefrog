behavior Modal
 init
  set @hx-disable to "true"
 end
 on click
   halt the event

   set link to @href
   set container to first <div[data-modals-container] />
   set template to first <div[data-modal-template] />

   set clone to template.children[0].cloneNode(true)
   append clone to container

   set content to first <div[data-modal-content] /> in clone
   set @hx-get of content to link
   set @hx-vals of content to '{"_sf_modal": "true"}'
   call htmx.process(content)

 end
end
