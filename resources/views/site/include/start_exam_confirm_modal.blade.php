{{-- Shared styled confirm for starting an exam (replaces window.confirm). --}}
<style>
  .startExamConfirm {
    position: fixed;
    inset: 0;
    z-index: 10050;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .startExamConfirm.is-open {
    display: flex;
  }
  .startExamConfirm__backdrop {
    position: absolute;
    inset: 0;
    background: rgba(17, 24, 39, 0.55);
  }
  .startExamConfirm__card {
    position: relative;
    width: min(440px, 100%);
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 18px 50px rgba(15, 23, 42, 0.28);
    overflow: hidden;
    animation: startExamConfirmIn 0.18s ease-out;
  }
  @keyframes startExamConfirmIn {
    from { opacity: 0; transform: translateY(8px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }
  .startExamConfirm__head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 20px 12px;
    border-bottom: 1px solid #f0e4ea;
  }
  .startExamConfirm__icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #eef2ff;
    color: #3561fe;
    font-size: 18px;
  }
  .startExamConfirm__card.is-proctored .startExamConfirm__icon {
    background: #fff1f5;
    color: #c2185b;
  }
  .startExamConfirm__title {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    line-height: 1.25;
  }
  .startExamConfirm__exam {
    margin: 2px 0 0;
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
  }
  .startExamConfirm__body {
    padding: 14px 20px 6px;
  }
  .startExamConfirm__msg {
    margin: 0 0 10px;
    font-size: 14px;
    color: #374151;
    line-height: 1.5;
  }
  .startExamConfirm__list {
    margin: 0 0 8px;
    padding-left: 18px;
    color: #4b5563;
    font-size: 13px;
    line-height: 1.55;
  }
  .startExamConfirm__list li + li {
    margin-top: 4px;
  }
  .startExamConfirm__badge {
    display: none;
    margin-top: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #c2185b;
    background: #fff1f5;
    border: 1px solid #f8c9da;
    border-radius: 999px;
    padding: 5px 10px;
    width: fit-content;
  }
  .startExamConfirm__card.is-proctored .startExamConfirm__badge {
    display: inline-flex;
  }
  .startExamConfirm__actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 14px 20px 18px;
  }
  .startExamConfirm__btn {
    border: none;
    border-radius: 10px;
    padding: 10px 16px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    min-width: 96px;
  }
  .startExamConfirm__btn--cancel {
    background: #f3f4f6;
    color: #374151;
  }
  .startExamConfirm__btn--cancel:hover {
    background: #e5e7eb;
  }
  .startExamConfirm__btn--ok {
    background: #3561fe;
    color: #fff;
  }
  .startExamConfirm__btn--ok:hover {
    background: #2548d4;
  }
  .startExamConfirm__card.is-proctored .startExamConfirm__btn--ok {
    background: #e91e63;
  }
  .startExamConfirm__card.is-proctored .startExamConfirm__btn--ok:hover {
    background: #c2185b;
  }
</style>

<div class="startExamConfirm" id="startExamConfirm" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="startExamConfirmTitle">
  <div class="startExamConfirm__backdrop" data-start-exam-cancel></div>
  <div class="startExamConfirm__card" id="startExamConfirmCard">
    <div class="startExamConfirm__head">
      <div class="startExamConfirm__icon" id="startExamConfirmIcon"><i class="fas fa-play"></i></div>
      <div>
        <h3 class="startExamConfirm__title" id="startExamConfirmTitle">Start exam?</h3>
        <p class="startExamConfirm__exam" id="startExamConfirmName"></p>
      </div>
    </div>
    <div class="startExamConfirm__body">
      <p class="startExamConfirm__msg" id="startExamConfirmMsg">Do you want to start this exam now?</p>
      <ul class="startExamConfirm__list" id="startExamConfirmList" style="display:none;"></ul>
      <div class="startExamConfirm__badge"><i class="fas fa-video me-1"></i> Proctored exam</div>
    </div>
    <div class="startExamConfirm__actions">
      <button type="button" class="startExamConfirm__btn startExamConfirm__btn--cancel" data-start-exam-cancel>Cancel</button>
      <button type="button" class="startExamConfirm__btn startExamConfirm__btn--ok" id="startExamConfirmOk">Start Exam</button>
    </div>
  </div>
</div>

<script>
  (function () {
    var modal = document.getElementById('startExamConfirm');
    if (!modal) return;

    var card = document.getElementById('startExamConfirmCard');
    var titleEl = document.getElementById('startExamConfirmTitle');
    var nameEl = document.getElementById('startExamConfirmName');
    var msgEl = document.getElementById('startExamConfirmMsg');
    var listEl = document.getElementById('startExamConfirmList');
    var iconEl = document.getElementById('startExamConfirmIcon');
    var okBtn = document.getElementById('startExamConfirmOk');
    var pendingUrl = '';

    function closeStartExamConfirm() {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      pendingUrl = '';
      document.body.style.overflow = '';
    }

    window.openStartExamConfirm = function (url, examName, isProctored) {
      pendingUrl = url || '';
      var proctored = !!isProctored && isProctored !== '0' && isProctored !== 'false';

      nameEl.textContent = examName || '';
      card.classList.toggle('is-proctored', proctored);

      if (proctored) {
        titleEl.textContent = 'Proctored exam';
        msgEl.textContent = 'Camera and fullscreen are required for the full duration of this exam.';
        listEl.style.display = 'block';
        listEl.innerHTML =
          '<li>Allow camera access before you begin.</li>' +
          '<li>Stay in fullscreen — leaving may lock or cancel the exam.</li>' +
          '<li>Do not switch tabs or cover the camera.</li>';
        iconEl.innerHTML = '<i class="fas fa-shield-alt"></i>';
        okBtn.textContent = 'Start Proctored Exam';
      } else {
        titleEl.textContent = 'Start exam?';
        msgEl.textContent = 'Do you want to start this exam now?';
        listEl.style.display = 'none';
        listEl.innerHTML = '';
        iconEl.innerHTML = '<i class="fas fa-play"></i>';
        okBtn.textContent = 'Start Exam';
      }

      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      okBtn.focus();
    };

    okBtn.addEventListener('click', function () {
      if (pendingUrl) {
        window.location.href = pendingUrl;
      }
    });

    modal.querySelectorAll('[data-start-exam-cancel]').forEach(function (el) {
      el.addEventListener('click', closeStartExamConfirm);
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modal.classList.contains('is-open')) {
        closeStartExamConfirm();
      }
    });
  })();
</script>
