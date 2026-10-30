// public/js/script.js – Same client‑side logic as prototype
// Uses localStorage for persistence; works inside Laravel Blade view.
const STORAGE_KEYS = {
  books: 'library_books',
  members: 'library_members',
  loans: 'library_loans'
};
function load(key) { const d = localStorage.getItem(key); return d ? JSON.parse(d) : []; }
function save(key, v) { localStorage.setItem(key, JSON.stringify(v)); }
let books = load(STORAGE_KEYS.books);
let members = load(STORAGE_KEYS.members);
let loans = load(STORAGE_KEYS.loans);
const modal = document.getElementById('modal');
const modalForm = document.getElementById('modal-form');
const modalClose = document.getElementById('modal-close');
modalClose.onclick = () => modal.classList.add('hidden');
window.onclick = e => { if (e.target === modal) modal.classList.add('hidden'); };
function openModal(title, fields, onSubmit, init = {}) {
  modalForm.innerHTML = '';
  const h = document.createElement('h3'); h.textContent = title; h.style.marginBottom='0.8rem'; modalForm.appendChild(h);
  fields.forEach(f=>{
    const label=document.createElement('label'); label.textContent=f.label;
    const input = f.type==='select'?document.createElement('select'):document.createElement('input');
    input.name=f.name; input.required=true; if(f.type!=='select') input.type=f.type;
    if(f.type==='select') f.options.forEach(o=>{ const opt=document.createElement('option'); opt.value=o.value; opt.textContent=o.label; input.appendChild(opt); });
    if(init[f.name]!==undefined) input.value=init[f.name];
    modalForm.appendChild(label); modalForm.appendChild(input);
  });
  const btn=document.createElement('button'); btn.type='submit'; btn.className='submit'; btn.textContent='Enregistrer'; modalForm.appendChild(btn);
  modalForm.onsubmit=e=>{e.preventDefault(); const data={}; fields.forEach(f=>{data[f.name]=modalForm.elements[f.name].value.trim();}); onSubmit(data); modal.classList.add('hidden');};
  modal.classList.remove('hidden');
}
function renderBooks(){ const tbody=document.querySelector('#books-table tbody'); tbody.innerHTML=''; books.forEach((b,i)=>{ const tr=document.createElement('tr'); tr.innerHTML=`<td>${b.title}</td><td>${b.author}</td><td>${b.isbn}</td><td>${b.totalCopies}</td><td class="action-icons"><button class="edit" data-idx="${i}">✏️</button><button class="delete" data-idx="${i}">🗑️</button></td>`; tbody.appendChild(tr);});}
function renderMembers(){ const tbody=document.querySelector('#members-table tbody'); tbody.innerHTML=''; members.forEach((m,i)=>{ const tr=document.createElement('tr'); tr.innerHTML=`<td>${m.name}</td><td>${m.email}</td><td class="action-icons"><button class="edit" data-idx="${i}">✏️</button><button class="delete" data-idx="${i}">🗑️</button></td>`; tbody.appendChild(tr);});}
function renderLoans(){ const tbody=document.querySelector('#loans-table tbody'); tbody.innerHTML=''; const filter=document.getElementById('member-filter').value; loans.forEach((l,i)=>{ if(filter!=='all' && l.memberId!==filter) return; const member=members.find(m=>m.id===l.memberId); const book=books.find(b=>b.isbn===l.isbn); const due=new Date(l.dueDate); const now=new Date(); let status=''; if(l.returnDate){status='<span class="badge returned">Rendu</span>';} else if(now>due){status='<span class="badge overdue">En retard</span>';} else {status='<span class="badge">En cours</span>';}
  const tr=document.createElement('tr'); tr.innerHTML=`<td>${member?member.name:'—'}</td><td>${book?book.title+' ('+book.isbn+')':l.isbn}</td><td>${l.startDate}</td><td>${l.dueDate}</td><td>${l.returnDate||'-'}</td><td>${status}</td><td class="action-icons">${l.returnDate?'':'<button class="return" data-idx="'+i+'">🔄 Retour</button>'}<button class="delete" data-idx="${i}">🗑️</button></td>`; tbody.appendChild(tr);});}
function refresh(){ renderBooks(); renderMembers(); renderLoans(); populateMemberFilter(); }
function addBook(d){ if(books.some(b=>b.isbn===d.isbn)){alert('ISBN déjà existant.');return;} books.push({title:d.title,author:d.author,isbn:d.isbn,totalCopies:Number(d.totalCopies)}); save(STORAGE_KEYS.books,books); renderBooks(); }
function editBook(i,d){ if(d.isbn!==books[i].isbn && books.some(b=>b.isbn===d.isbn)){alert('ISBN déjà existant.');return;} books[i]={title:d.title,author:d.author,isbn:d.isbn,totalCopies:Number(d.totalCopies)}; save(STORAGE_KEYS.books,books); renderBooks(); }
function deleteBook(i){ if(!confirm('Supprimer ce livre ?'))return; const isbn=books[i].isbn; loans=loans.filter(l=>l.isbn!==isbn); save(STORAGE_KEYS.loans,loans); books.splice(i,1); save(STORAGE_KEYS.books,books); refresh(); }
function addMember(d){ if(members.some(m=>m.email===d.email)){alert('Email déjà utilisé.');return;} const id='m_'+Date.now()+Math.random().toString(16).slice(2); members.push({id,name:d.name,email:d.email}); save(STORAGE_KEYS.members,members); renderMembers(); populateMemberFilter(); }
function editMember(i,d){ if(d.email!==members[i].email && members.some(m=>m.email===d.email)){alert('Email déjà utilisé.');return;} members[i].name=d.name; members[i].email=d.email; save(STORAGE_KEYS.members,members); renderMembers(); populateMemberFilter(); }
function deleteMember(i){ if(!confirm('Supprimer ce membre ?'))return; const id=members[i].id; loans=loans.filter(l=>l.memberId!==id); save(STORAGE_KEYS.loans,loans); members.splice(i,1); save(STORAGE_KEYS.members,members); refresh(); }
function addLoan(d){ const member=members.find(m=>m.id===d.memberId); const book=books.find(b=>b.isbn===d.isbn); if(!member||!book){alert('Membre ou livre introuvable');return;} const start=new Date().toISOString().split('T')[0]; const due=new Date(); due.setDate(due.getDate()+14); const dueStr=due.toISOString().split('T')[0]; loans.push({memberId:member.id,isbn:book.isbn,startDate:start,dueDate:dueStr,returnDate:null}); save(STORAGE_KEYS.loans,loans); renderLoans(); }
function returnLoan(i){ loans[i].returnDate=new Date().toISOString().split('T')[0]; save(STORAGE_KEYS.loans,loans); renderLoans(); }
function deleteLoan(i){ if(!confirm('Supprimer cet emprunt ?'))return; loans.splice(i,1); save(STORAGE_KEYS.loans,loans); renderLoans(); }
function populateMemberFilter(){ const sel=document.getElementById('member-filter'); const cur=sel.value; sel.innerHTML='<option value="all">Tous les membres</option>'; members.forEach(m=>{ const opt=document.createElement('option'); opt.value=m.id; opt.textContent=m.name; sel.appendChild(opt); }); if(members.some(m=>m.id===cur)) sel.value=cur; }
// Event bindings
document.getElementById('add-book-btn').onclick=()=>openModal('Ajouter Livre',[{name:'title',label:'Titre',type:'text'},{name:'author',label:'Auteur',type:'text'},{name:'isbn',label:'ISBN',type:'text'},{name:'totalCopies',label:'Exemplaires totaux',type:'number'}],addBook);
document.getElementById('add-member-btn').onclick=()=>openModal('Ajouter Membre',[{name:'name',label:'Nom',type:'text'},{name:'email',label:'Email',type:'email'}],addMember);
document.getElementById('add-loan-btn').onclick=()=>{ if(members.length===0||books.length===0){alert('Ajoutez d’abord au moins un membre et un livre.');return;} openModal('Nouvel Emprunt',[{name:'memberId',label:'Membre',type:'select',options:members.map(m=>({value:m.id,label:m.name}))},{name:'isbn',label:'Livre (ISBN)',type:'select',options:books.map(b=>({value:b.isbn,label:b.title+' ('+b.isbn+')'}))}],addLoan); };
// Tab navigation
document.querySelectorAll('.tab').forEach(t=>{t.onclick=()=>{ document.querySelectorAll('.tab').forEach(x=>x.classList.remove('active')); t.classList.add('active'); const target=t.dataset.target; document.querySelectorAll('.panel').forEach(p=>p.classList.add('hidden')); document.getElementById(target).classList.remove('hidden'); };});
// Table actions
document.getElementById('books-table').addEventListener('click',e=>{ if(e.target.matches('button.edit')){ const i=e.target.dataset.idx; const b=books[i]; openModal('Modifier Livre',[{name:'title',label:'Titre',type:'text'},{name:'author',label:'Auteur',type:'text'},{name:'isbn',label:'ISBN',type:'text'},{name:'totalCopies',label:'Exemplaires totaux',type:'number'}],data=>editBook(i,data),b); } if(e.target.matches('button.delete')){ const i=e.target.dataset.idx; deleteBook(i); } });
document.getElementById('members-table').addEventListener('click',e=>{ if(e.target.matches('button.edit')){ const i=e.target.dataset.idx; const m=members[i]; openModal('Modifier Membre',[{name:'name',label:'Nom',type:'text'},{name:'email',label:'Email',type:'email'}],data=>editMember(i,data),m); } if(e.target.matches('button.delete')){ const i=e.target.dataset.idx; deleteMember(i); } });
document.getElementById('loans-table').addEventListener('click',e=>{ if(e.target.matches('button.return')){ const i=e.target.dataset.idx; returnLoan(i); } if(e.target.matches('button.delete')){ const i=e.target.dataset.idx; deleteLoan(i); } });
document.getElementById('member-filter').addEventListener('change',renderLoans);
refresh();
