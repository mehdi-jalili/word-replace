const RemoveRuleModal = document.getElementById("RemoveRuleModal");
const removeRuleBtn = document.getElementsByClassName("delete-rule-word");
const span = document.getElementsByClassName("close")[0];
const cancelRuleBtn = document.getElementById("cancelRuleBtn");
const deleteRuleBtn = document.getElementById("deleteRuleBtn");

function replaceModalDisplayNone() {
  RemoveRuleModal.style.display = "none";
}

function replaceModalDisplayBlock() {
  RemoveRuleModal.style.display = "block";
}

span.onclick = () => {
  replaceModalDisplayNone();
};

cancelRuleBtn.onclick = () => {
  replaceModalDisplayNone();
};

window.onclick = (event) => {
  if (event.target == RemoveRuleModal) {
    replaceModalDisplayNone();
  }
};

Array.from(removeRuleBtn).forEach(button => {
  button.onclick = (event) => {
    const buttonId = event.target.getAttribute('id');
    getButtonId(buttonId);
  }
});

function getButtonId(buttonId) {
  replaceModalDisplayBlock();
  deleteRuleBtn.value = buttonId;
}
