class UsernameGenerator
  attr_reader :generated

  def initialize()
    #@len = len
    @generated = []
  end

  def get_user_id
    random_string = (0..7).map{ rand(36).to_s(36) }.join
    @user_id =  "test_" + random_string
    #p @user_id
    return @user_id
    @user_data['user_name'] = @user_id
    File.open((File.join(File.dirname(__FILE__), '..', '..', 'config', 'user_data.yml')), 'w') { |f| f.write @user_data.to_yaml}
    end

  def get_first_name
    random_string_first_name = (0...8).map{ ('A'..'Z').to_a[rand(26)]  }.join
    (0...8).map{ ('A'..'Z').to_a[rand(26)]  }.join
    @first_name=  random_string_first_name
    #p @user_id
    return @first_name
  end

  def get_last_name
    random_string_last_name = (0...8).map{ ('A'..'Z').to_a[rand(26)]  }.join
    (0...8).map{ ('A'..'Z').to_a[rand(26)]  }.join
    @last_name=  random_string_last_name
    #p @user_id
    return @last_name
  end




end