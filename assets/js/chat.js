(() => {
  const form = document.getElementById("typemessage-container");
  const messageList = document.getElementById("messages");
  if (!form || !messageList) return;

  const composer = form.querySelector("textarea[name='typemessage']");
  const submit = form.querySelector("button[type='submit']");
  const status = document.getElementById("chat-status");
  let requestPending = false;

  const setStatus = (message) => {
    if (status) status.textContent = message;
  };

  const hasSelectedContact = messageList.dataset.selected === "1";
  if (!hasSelectedContact) {
    setStatus("Select a contact to send a message.");
    return;
  }

  const refreshMessages = async () => {
    try {
      const response = await fetch("display.php", { cache: "no-store" });
      if (!response.ok) throw new Error("Message refresh failed");
      const html = await response.text();
      const content = html.trim() || '<p class="chat-empty-state">No messages yet. Start the conversation.</p>';
      if (content !== messageList.innerHTML) {
        messageList.innerHTML = content;
        messageList.scrollTop = 0;
      }
    } catch (_error) {
      setStatus("Messages could not refresh. Retrying shortly.");
    }
  };

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (requestPending || !composer || !composer.value.trim()) return;

    requestPending = true;
    if (submit) submit.disabled = true;
    setStatus("Sending...");
    try {
      const response = await fetch(form.action, {
        method: "POST",
        body: new FormData(form),
        headers: { "X-Requested-With": "XMLHttpRequest", Accept: "application/json" },
        cache: "no-store",
      });
      if (!response.ok) throw new Error("Message send failed");
      const result = await response.json();
      if (!result.ok) throw new Error("Message was not saved");
      composer.value = "";
      setStatus("Message sent.");
      await refreshMessages();
    } catch (_error) {
      setStatus("Message could not be sent. Please try again.");
    } finally {
      requestPending = false;
      if (submit) submit.disabled = false;
    }
  });

  refreshMessages();
  window.setInterval(refreshMessages, 2500);
})();
