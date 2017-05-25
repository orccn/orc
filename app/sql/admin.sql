create table admin_user(id number(10),username varchar2(30),passwd varchar2(72))
create sequence seq_admin_user_id       
       increment by 1 --每次增加几个，我这里是每次增加1  
       start with 1000   --从1开始计数  
       nomaxvalue      --不设置最大值  
       nocycle         --一直累加，不循环  
       nocache;        --不建缓冲区  

create trigger trg_admin_user_id before  
insert on admin_user for each row when(new.id is null)   
begin  
select seq_admin_user_id.nextval into:new.id from dual;  
end;    

