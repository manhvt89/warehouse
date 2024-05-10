--DROP TABLE IF EXISTS `ospos_`;
-- DROP TABLE IF EXISTS `ospos_app_config
-- DROP TABLE IF EXISTS `ospos_grants
-- DROP TABLE IF EXISTS `ospos_modules                                        
-- DROP TABLE IF EXISTS `ospos_permissions
-- DROP TABLE IF EXISTS `ospos_role_permissions           
-- DROP TABLE IF EXISTS `ospos_roles  
-- DROP TABLE IF EXISTS `ospos_user_roles 
-- DROP TABLE IF EXISTS `ospos_employees`;
-- DROP TABLE IF EXISTS `ospos_people
delete from ospos_people where person_id NOT IN (select person_id from ospos_employees);

DROP TABLE IF EXISTS `ospos_customers`;                
DROP TABLE IF EXISTS `ospos_daily_total`;               
                  
DROP TABLE IF EXISTS `ospos_fields`;                     
DROP TABLE IF EXISTS `ospos_giftcards`;                  
                    
DROP TABLE IF EXISTS `ospos_history_points`;             
DROP TABLE IF EXISTS `ospos_inventory`;                  
DROP TABLE IF EXISTS `ospos_item_kit_items`;             
DROP TABLE IF EXISTS `ospos_item_kits`;                  
DROP TABLE IF EXISTS `ospos_item_quantities`;            
DROP TABLE IF EXISTS `ospos_items`;                      
DROP TABLE IF EXISTS `ospos_items_taxes`;                
DROP TABLE IF EXISTS `ospos_messages`;                   
                
DROP TABLE IF EXISTS `ospos_purchases`;                  
DROP TABLE IF EXISTS `ospos_purchases_items`;            
DROP TABLE IF EXISTS `ospos_receivings`;                 
DROP TABLE IF EXISTS `ospos_receivings_items`;           
DROP TABLE IF EXISTS `ospos_reminders`;                  
DROP TABLE IF EXISTS `ospos_reports_detail_sales`;       
                    
DROP TABLE IF EXISTS `ospos_sales`;                      
DROP TABLE IF EXISTS `ospos_sales_items`;                
DROP TABLE IF EXISTS `ospos_sales_items_taxes`;          
DROP TABLE IF EXISTS `ospos_sales_payments`;             
DROP TABLE IF EXISTS `ospos_sales_suspended`;            
DROP TABLE IF EXISTS `ospos_sales_suspended_items`;      
DROP TABLE IF EXISTS `ospos_sales_suspended_items_taxes`;
DROP TABLE IF EXISTS `ospos_sales_suspended_payments`;   
DROP TABLE IF EXISTS `ospos_sessions`;                   
DROP TABLE IF EXISTS `ospos_short_survey`;               
DROP TABLE IF EXISTS `ospos_sms_sale`;                   
DROP TABLE IF EXISTS `ospos_stock_locations`;            
DROP TABLE IF EXISTS `ospos_suppliers`;                  
DROP TABLE IF EXISTS `ospos_test`;                       
DROP TABLE IF EXISTS `ospos_total`;      
DROP TABLE IF EXISTS `ospos_history_ctv`;                
                